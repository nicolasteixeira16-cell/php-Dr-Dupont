<?php

require_once 'helpers/auth.php';
require_once 'models/RendezVousManager.php';
require_once 'models/ServiceManager.php';
require_once 'models/UtilisateurManager.php';
require_once 'models/ActualiteManager.php';
require_once 'models/AProposManager.php';
require_once 'models/PatientManager.php';
require_once 'models/HoraireManager.php';
require_once 'models/AccueilManager.php';

class AdminController {

    // --- Connexion de l'equipe ---
    // Meme trio de methodes que PatientController::login()/loginValid(),
    // mais pour le staff, avec sa propre cle de session "staff_id".
    // Pas de requireStaffAuth() ici : ce sont justement les methodes
    // qui servent a se connecter, donc personne n'est encore connecte
    // au moment ou elles s'executent.

    public function adminLogin() {
        require 'views/admin-login.php';
    }

    public function adminLoginValid(): void {
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        if (!$email || !$password) {
            header("Location: index.php?page=admin-login&error=fields");
            exit;
        }

        $utilisateurManager = new UtilisateurManager();
        $utilisateur = $utilisateurManager->getUtilisateur($email);

        if (!$utilisateur) {
            header("Location: index.php?page=admin-login&error=credentials");
            exit;
        }
        if (!password_verify($password, $utilisateur->getPassword())) {
            header("Location: index.php?page=admin-login&error=credentials");
            exit;
        }

        $_SESSION["staff_id"] = $utilisateur->getId();
        $_SESSION["staff_name"] = $utilisateur->getNomComplet();
        $_SESSION["staff_role"] = $utilisateur->getRole();
        header("Location: index.php?page=admin");
        exit;
    }

    public function adminLogout(): void {
        unset($_SESSION["staff_id"]);
        unset($_SESSION["staff_name"]);
        unset($_SESSION["staff_role"]);
        header("Location: index.php?page=home");
        exit;
    }

    // --- Tableau de bord et rendez-vous ---

    // Le tableau de bord affiche un resume chiffre
    // (nombre de rendez-vous, patients, services). On recupere les 3 listes completes
    // via les Managers existants, et on compte leurs
    // elements avec count() - pas besoin d'une requete SQL "COUNT(*)"
    // dediee pour un projet de cette taille.
    public function admin() {
        requireStaffAuth();

        $rendezVousManager = new RendezVousManager();
        $patientManager = new PatientManager();
        $serviceManager = new ServiceManager();

        $nombreRendezVous = count($rendezVousManager->getAllRendezVous());
        $nombrePatients = count($patientManager->getPatients());
        $nombreServices = count($serviceManager->getServices());

        require 'views/admin.php';
    }

    public function adminAppointments() {
        requireStaffAuth();
        $rendezVousManager = new RendezVousManager();
        $rendezVous = $rendezVousManager->getAllRendezVous();
        require 'views/admin-appointments.php';
    }

    public function confirmAppointment(): void {
        requireStaffAuth();
        if (!isset($_GET["id"])) {
            header("Location: index.php?page=admin-appointments");
            exit;
        }
        $id = (int)$_GET["id"];
        $rendezVousManager = new RendezVousManager();
        $rendezVousManager->updateStatut($id, "confirme");
        header("Location: index.php?page=admin-appointments");
        exit;
    }

    public function cancelAppointment(): void {
        requireStaffAuth();
        if (!isset($_GET["id"])) {
            header("Location: index.php?page=admin-appointments");
            exit;
        }
        $id = (int)$_GET["id"];
        $rendezVousManager = new RendezVousManager();
        $rendezVousManager->updateStatut($id, "annule");
        header("Location: index.php?page=admin-appointments");
        exit;
    }

    // Modifier un rendez-vous existant (date/heure/service), a ne pas
    // confondre avec confirmAppointment()/cancelAppointment() qui ne
    // touchent qu'au statut.
    public function editAppointment() {
        requireStaffAuth();
        if (!isset($_GET["id"])) {
            header("Location: index.php?page=admin-appointments");
            exit;
        }
        $rendezVousManager = new RendezVousManager();
        $rendezVous = $rendezVousManager->getRendezVous((int)$_GET["id"]);
        if (!$rendezVous) {
            header("Location: index.php?page=admin-appointments");
            exit;
        }
        $serviceManager = new ServiceManager();
        $services = $serviceManager->getServices();
        require 'views/edit-appointment.php';
    }

    public function editAppointmentValid(): void {
        requireStaffAuth();

        $id = (int)($_POST["id"] ?? 0);
        $idService = $_POST["id_service"] ?? "";
        $date = $_POST["date"] ?? "";
        $heure = $_POST["heure"] ?? "";

        if (!$id || !$idService || !$date || !$heure) {
            header("Location: index.php?page=edit-appointment&id=$id&error=fields");
            exit;
        }

        $rendezVousManager = new RendezVousManager();
        $rendezVousManager->updateRendezVous($id, (int)$idService, $date, $heure);
        header("Location: index.php?page=admin-appointments");
        exit;
    }

    // --- Services ---

    public function adminServices() {
        requireStaffAuth();
        $serviceManager = new ServiceManager();
        $services = $serviceManager->getServices();
        require 'views/admin-services.php';
    }

    public function createService() {
        requireStaffAuth();
        require 'views/create-service.php';
    }

    public function createServiceValid(): void {
        requireStaffAuth();

        $nom = trim($_POST["nom"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $duree = (int)($_POST["duree"] ?? 0);
        $prix = (float)($_POST["prix"] ?? 0);

        if (strlen($nom) < 3 || strlen($description) < 10 || $duree <= 0) {
            header("Location: index.php?page=create-service&error=true");
            exit;
        }

        $serviceManager = new ServiceManager();
        $serviceManager->createService($nom, $description, $duree, $prix);
        header("Location: index.php?page=admin-services");
        exit;
    }

    public function deleteService(): void {
        requireStaffAuth();
        if (!isset($_GET["id"])) {
            header("Location: index.php?page=admin-services");
            exit;
        }
        $id = (int)$_GET["id"];
        $serviceManager = new ServiceManager();
        $serviceManager->deleteService($id);
        header("Location: index.php?page=admin-services");
        exit;
    }

    // Meme principe que createService()/createServiceValid(), mais on
    // pre-remplit le formulaire avec les valeurs actuelles (edit-service.php)
    // et on appelle updateService() plutot que createService().
    public function editService() {
        requireStaffAuth();
        if (!isset($_GET["id"])) {
            header("Location: index.php?page=admin-services");
            exit;
        }
        $serviceManager = new ServiceManager();
        $service = $serviceManager->getService((int)$_GET["id"]);
        if (!$service) {
            header("Location: index.php?page=admin-services");
            exit;
        }
        require 'views/edit-service.php';
    }

    public function editServiceValid(): void {
        requireStaffAuth();

        $id = (int)($_POST["id"] ?? 0);
        $nom = trim($_POST["nom"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $duree = (int)($_POST["duree"] ?? 0);
        $prix = (float)($_POST["prix"] ?? 0);

        if (strlen($nom) < 3 || strlen($description) < 10 || $duree <= 0) {
            header("Location: index.php?page=edit-service&id=$id&error=true");
            exit;
        }

        $serviceManager = new ServiceManager();
        $serviceManager->updateService($id, $nom, $description, $duree, $prix);
        header("Location: index.php?page=admin-services");
        exit;
    }

    // --- Page "A propos" ---
    // Meme trio show/valid que pour un service ou une actualite,
    // mais il n'y a jamais qu'UNE seule chose a modifier (pas de liste,
    // pas de "supprimer").

    public function adminAbout() {
        requireStaffAuth();
        $aProposManager = new AProposManager();
        $aPropos = $aProposManager->getAPropos();
        require 'views/admin-about.php';
    }

    public function adminAboutValid(): void {
        requireStaffAuth();

        $contenu = trim($_POST["contenu"] ?? "");

        if (!$contenu) {
            header("Location: index.php?page=admin-about&error=true");
            exit;
        }

        $aProposManager = new AProposManager();
        $aProposManager->updateAPropos($contenu);
        header("Location: index.php?page=about");
        exit;
    }

    // --- Actualites ---
    // Meme structure que Services (adminServices/createService/createServiceValid/
    // deleteService), avec en plus la gestion d'une image.

    public function adminNews() {
        requireStaffAuth();
        $actualiteManager = new ActualiteManager();
        $actualites = $actualiteManager->getActualites();
        require 'views/admin-news.php';
    }

    public function createNews() {
        requireStaffAuth();
        require 'views/create-news.php';
    }

    public function createNewsValid(): void {
        requireStaffAuth();

        $titre = trim($_POST["titre"] ?? "");
        $contenu = trim($_POST["contenu"] ?? "");

        if (strlen($titre) < 3 || strlen($contenu) < 10) {
            header("Location: index.php?page=create-news&error=fields");
            exit;
        }

        // L'image est FACULTATIVE (contrairement au titre/contenu),
        // donc on ne bloque rien si aucun fichier n'a ete choisi -
        // $image reste simplement a null.
        $image = null;

        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
            $extensionsAutorisees = ["jpg", "jpeg", "png", "webp"];
            $extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

            if (!in_array($extension, $extensionsAutorisees)) {
                header("Location: index.php?page=create-news&error=extension");
                exit;
            }

            // 5 Mo maximum
            if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
                header("Location: index.php?page=create-news&error=size");
                exit;
            }

            // Nom de fichier unique pour eviter qu'une nouvelle image
            // ecrase une image existante qui porterait le meme nom.
            $newFileName = uniqid() . "." . $extension;
            move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $newFileName);
            $image = $newFileName;
        }

        $actualiteManager = new ActualiteManager();
        $actualiteManager->createActualite($titre, $contenu, $image, $_SESSION["staff_id"]);
        header("Location: index.php?page=admin-news");
        exit;
    }

    public function deleteNews(): void {
        requireStaffAuth();
        if (!isset($_GET["id"])) {
            header("Location: index.php?page=admin-news");
            exit;
        }
        $id = (int)$_GET["id"];
        $actualiteManager = new ActualiteManager();
        $actualiteManager->deleteActualite($id);
        header("Location: index.php?page=admin-news");
        exit;
    }

    public function editNews() {
        requireStaffAuth();
        if (!isset($_GET["id"])) {
            header("Location: index.php?page=admin-news");
            exit;
        }
        $actualiteManager = new ActualiteManager();
        $actualite = $actualiteManager->getActualite((int)$_GET["id"]);
        if (!$actualite) {
            header("Location: index.php?page=admin-news");
            exit;
        }
        require 'views/edit-news.php';
    }

    public function editNewsValid(): void {
        requireStaffAuth();

        $id = (int)($_POST["id"] ?? 0);
        $titre = trim($_POST["titre"] ?? "");
        $contenu = trim($_POST["contenu"] ?? "");

        if (strlen($titre) < 3 || strlen($contenu) < 10) {
            header("Location: index.php?page=edit-news&id=$id&error=fields");
            exit;
        }

        $actualiteManager = new ActualiteManager();
        // On recupere d'abord l'actualite existante : si aucune NOUVELLE
        // image n'est envoyee, on doit reutiliser l'ancienne au lieu de
        // l'effacer
        // ($image = $newFileName ?? $article->getImage();).
        $actualiteExistante = $actualiteManager->getActualite($id);
        $image = $actualiteExistante ? $actualiteExistante->getImage() : null;

        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
            $extensionsAutorisees = ["jpg", "jpeg", "png", "webp"];
            $extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

            if (!in_array($extension, $extensionsAutorisees)) {
                header("Location: index.php?page=edit-news&id=$id&error=extension");
                exit;
            }
            if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
                header("Location: index.php?page=edit-news&id=$id&error=size");
                exit;
            }

            $newFileName = uniqid() . "." . $extension;
            move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $newFileName);
            $image = $newFileName;
        }

        $actualiteManager->updateActualite($id, $titre, $contenu, $image);
        header("Location: index.php?page=admin-news");
        exit;
    }

    // --- Gestion du personnel back-office ---
    // Meme trio show/create/createValid que pour les services et les
    // actualites, mais protege par requireAdminAuth() au lieu de
    // requireStaffAuth() : il ne suffit pas d'etre connecte, il faut
    // en plus avoir le role "administrateur" (le Dr. Dupont).

    public function adminStaff() {
        requireAdminAuth();
        $utilisateurManager = new UtilisateurManager();
        $utilisateurs = $utilisateurManager->getUtilisateurs();
        require 'views/admin-staff.php';
    }

    public function createStaff() {
        requireAdminAuth();
        require 'views/create-staff.php';
    }

    public function createStaffValid(): void {
        requireAdminAuth();

        $nom = trim($_POST["nom"] ?? "");
        $prenom = trim($_POST["prenom"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $role = $_POST["role"] ?? "assistant";

        if (!$nom || !$prenom || !$email || !$password) {
            header("Location: index.php?page=create-staff&error=fields");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: index.php?page=create-staff&error=email-format");
            exit;
        }

        $utilisateurManager = new UtilisateurManager();

        if ($utilisateurManager->emailAlreadyExists($email)) {
            header("Location: index.php?page=create-staff&error=email-used");
            exit;
        }

        // On ne fait jamais confiance a une valeur "role" venue d'ailleurs
        // que du formulaire qu'on affiche nous-memes : si quelqu'un
        // bidouille la requete pour envoyer autre chose, on retombe sur
        // "assistant" par securite plutot que de creer un administrateur.
        if ($role !== "administrateur" && $role !== "assistant") {
            $role = "assistant";
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $utilisateurManager->createUtilisateur($nom, $prenom, $email, $passwordHash, $role);
        header("Location: index.php?page=admin-staff");
        exit;
    }

    // --- Gestion des patients (nouveau) ---
    // Meme trio "liste / creer / modifier / supprimer" que pour les
    // services, mais applique a la table patients. Un patient cree
    // depuis le back-office reutilise PatientManager::register(), la
    // meme methode que le formulaire d'inscription public utilise deja.

    public function adminPatients() {
        requireStaffAuth();
        $patientManager = new PatientManager();
        $patients = $patientManager->getPatients();
        require 'views/admin-patients.php';
    }

    public function createPatient() {
        requireStaffAuth();
        require 'views/create-patient.php';
    }

    public function createPatientValid(): void {
        requireStaffAuth();

        $nom = trim($_POST["nom"] ?? "");
        $prenom = trim($_POST["prenom"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $telephone = trim($_POST["telephone"] ?? "");
        $password = $_POST["password"] ?? "";

        if (!$nom || !$prenom || !$email || !$password) {
            header("Location: index.php?page=create-patient&error=fields");
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: index.php?page=create-patient&error=email-format");
            exit;
        }

        $patientManager = new PatientManager();
        if ($patientManager->emailAlreadyExists($email)) {
            header("Location: index.php?page=create-patient&error=email-used");
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $patientManager->register($nom, $prenom, $email, $telephone, $passwordHash);
        header("Location: index.php?page=admin-patients");
        exit;
    }

    public function editPatient() {
        requireStaffAuth();
        if (!isset($_GET["id"])) {
            header("Location: index.php?page=admin-patients");
            exit;
        }
        $patientManager = new PatientManager();
        $patient = $patientManager->getPatientById((int)$_GET["id"]);
        if (!$patient) {
            header("Location: index.php?page=admin-patients");
            exit;
        }
        require 'views/edit-patient.php';
    }

    public function editPatientValid(): void {
        requireStaffAuth();

        $id = (int)($_POST["id"] ?? 0);
        $nom = trim($_POST["nom"] ?? "");
        $prenom = trim($_POST["prenom"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $telephone = trim($_POST["telephone"] ?? "");

        if (!$nom || !$prenom || !$email) {
            header("Location: index.php?page=edit-patient&id=$id&error=fields");
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: index.php?page=edit-patient&id=$id&error=email-format");
            exit;
        }

        $patientManager = new PatientManager();
        $patientManager->updatePatient($id, $nom, $prenom, $email, $telephone);
        header("Location: index.php?page=admin-patients");
        exit;
    }

    public function deletePatient(): void {
        requireStaffAuth();
        if (!isset($_GET["id"])) {
            header("Location: index.php?page=admin-patients");
            exit;
        }
        $patientManager = new PatientManager();
        $patientManager->deletePatient((int)$_GET["id"]);
        header("Location: index.php?page=admin-patients");
        exit;
    }

    // --- Gestion des horaires (nouveau) ---
    // Contrairement aux autres sections, il n'y a jamais de creation
    // ni de suppression : les 7 jours existent toujours, on ne fait
    // que les modifier - un seul formulaire pour les 7 a la fois.

    public function adminHoraires() {
        requireStaffAuth();
        $horaireManager = new HoraireManager();
        $horaires = $horaireManager->getHoraires();
        require 'views/admin-horaires.php';
    }

    public function adminHorairesValid(): void {
        requireStaffAuth();

        $horaireManager = new HoraireManager();

        // Le formulaire envoie un tableau par jour (voir admin-horaires.php :
        // les champs s'appellent ferme[lundi], heure_ouverture[lundi], etc.).
        // On boucle sur les 7 jours plutot que d'avoir 7 blocs de code
        // identiques - exactement le meme reflexe qu'un foreach sur
        // $services au lieu de repeter le HTML de chaque service a la main.
        $jours = ["lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche"];

        foreach ($jours as $jour) {
            $ferme = isset($_POST["ferme"][$jour]);
            // Un jour ferme n'a pas d'horaires : on force les deux
            // champs a null plutot que de garder d'anciennes valeurs
            // qui n'auraient plus de sens (coherence des donnees).
            $heureOuverture = $ferme ? null : ($_POST["heure_ouverture"][$jour] ?? null);
            $heureFermeture = $ferme ? null : ($_POST["heure_fermeture"][$jour] ?? null);

            $horaireManager->updateHoraire($jour, $heureOuverture ?: null, $heureFermeture ?: null, $ferme);
        }

        header("Location: index.php?page=admin-horaires&success=true");
        exit;
    }

    // --- Gestion de l'encart d'accueil (nouveau) ---
    // Meme trio show/valid que pour "A propos", MAIS protege par
    // requireAdminAuth() au lieu de requireStaffAuth() : c'est reserve
    // au Dr. Dupont, comme demande, pas a n'importe quel membre du
    // personnel (contrairement a "A propos", "Actualites" ou "Services").

    public function adminHome() {
        requireAdminAuth();
        $accueilManager = new AccueilManager();
        $accueil = $accueilManager->getAccueil();
        require 'views/admin-home.php';
    }

    public function adminHomeValid(): void {
        requireAdminAuth();

        $titre = trim($_POST["titre"] ?? "");
        $texte = trim($_POST["texte"] ?? "");

        if (!$titre || !$texte) {
            header("Location: index.php?page=admin-home&error=fields");
            exit;
        }

        $accueilManager = new AccueilManager();
        // Meme "reflexe image" que pour editNewsValid() : si aucune
        // NOUVELLE image n'est envoyee, on garde l'ancienne au lieu
        // de l'effacer.
        $accueilExistant = $accueilManager->getAccueil();
        $image = $accueilExistant ? $accueilExistant->getImage() : null;

        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
            $extensionsAutorisees = ["jpg", "jpeg", "png", "webp"];
            $extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

            if (!in_array($extension, $extensionsAutorisees)) {
                header("Location: index.php?page=admin-home&error=extension");
                exit;
            }
            if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
                header("Location: index.php?page=admin-home&error=size");
                exit;
            }

            $newFileName = uniqid() . "." . $extension;
            move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $newFileName);
            $image = $newFileName;
        }

        $accueilManager->updateAccueil($titre, $texte, $image);
        header("Location: index.php?page=home");
        exit;
    }
}
