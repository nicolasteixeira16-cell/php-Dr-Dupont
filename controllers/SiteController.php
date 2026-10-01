<?php

require_once 'models/ServiceManager.php';
require_once 'models/RendezVousManager.php';
require_once 'models/ActualiteManager.php';
require_once 'models/AProposManager.php';
require_once 'models/HoraireManager.php';
require_once 'models/AccueilManager.php';

class SiteController {

    
    // On récupére des articles, on récupère les services
    // (pour les montrer en aperçu sur l'accueil, par exemple).
    public function home() {
        $serviceManager = new ServiceManager();
        $services = $serviceManager->getServices();
        // Nouveau : le contenu du grand encart (titre/texte/image) vient
        // maintenant de la base, exactement comme la page "A propos".
        $accueilManager = new AccueilManager();
        $accueil = $accueilManager->getAccueil();
        require 'views/home.php';
    }

    // Page "Nos services" : liste complète
    public function services() {
        $serviceManager = new ServiceManager();
        $services = $serviceManager->getServices();
        require 'views/services.php';
    }

    // Page "A propos" : ce n'est pas une page statique. Le contenu
    // vient de la base (table a_propos), pour que le
    // back-office puisse le modifier sans toucher au code - exactement
    // comme le contenu d'une actualite ou d'un service.
    public function about() {
        $aProposManager = new AProposManager();
        $aPropos = $aProposManager->getAPropos();
        require 'views/about.php';
    }

    // Page actualites
    public function news() {
        $actualiteManager = new ActualiteManager();
        $actualites = $actualiteManager->getActualites();
        require 'views/news.php';
    }

    // Nouvelle page. On affiche
    // une seule actualite en entier
    public function newsDetail() {
        if (!isset($_GET["id"])) {
            header("Location: index.php?page=news");
            exit;
        }
        $actualiteManager = new ActualiteManager();
        $actualite = $actualiteManager->getActualite((int)$_GET["id"]);
        if (!$actualite) {
            header("Location: index.php?page=news");
            exit;
        }
        require 'views/news-detail.php';
    }

    // Affiche le FORMULAIRE de prise de rendez-vous.
    // On a besoin de la liste des services pour remplir le menu
    // deroulant "type de consultation" dans le formulaire
    public function appointment() {
        $serviceManager = new ServiceManager();
        $services = $serviceManager->getServices();
        // on recupere aussi les horaires, pour les afficher
        // sur le formulaire. Ce n'est qu'un rappel visuel pour le
        // patient - la verification qui compte reellement se fait
        // cote serveur, dans appointmentValid() ci-dessous.
        $horaireManager = new HoraireManager();
        $horaires = $horaireManager->getHoraires();
        require 'views/appointment.php';
    }

    // Traite l'ENVOI du formulaire de rendez-vous.
    // on recupere les champs, on verifie qu'ils sont valides,
    // puis on enregistre ou on redirige avec une erreur.
    public function appointmentValid(): void {
        $idService = $_POST["id_service"] ?? "";
        $date = $_POST["date"] ?? "";
        $heure = $_POST["heure"] ?? "";

        // Si le patient n'est pas connecte, on ne peut pas savoir
        // a qui rattacher le rendez-vous : on le renvoie se connecter.
        if (!isset($_SESSION["patient_id"])) {
            header("Location: index.php?page=login");
            exit;
        }

        // Meme logique en cascade que loginValid() : on verifie
        // chaque champ, et au premier probleme on s'arrete.
        if (!$idService || !$date || !$heure) {
            header("Location: index.php?page=appointment&error=fields");
            exit;
        }

        // verification des horaires d'ouverture ---
        // On retrouve le NOM du jour (lundi, mardi...) a partir de la
        // date choisie. date("N", ...) donne un chiffre de 1 (lundi) a
        // 7 (dimanche) - exactement l'ordre dans lequel les jours sont
        // ranges dans le tableau ci-dessous (attention : l'index 0 ne
        // sert a rien, on le laisse simplement vide pour que l'index 1
        // corresponde bien a "lundi").
        $joursSemaine = [1 => "lundi", 2 => "mardi", 3 => "mercredi", 4 => "jeudi", 5 => "vendredi", 6 => "samedi", 7 => "dimanche"];
        $jour = $joursSemaine[(int)date("N", strtotime($date))];

        $horaireManager = new HoraireManager();
        $horaire = $horaireManager->getHoraireByJour($jour);

        // Si le jour est ferme (ou n'existe meme pas en base, ce qui ne
        // devrait pas arriver mais on se protege quand meme), on refuse.
        if (!$horaire || $horaire->estFerme()) {
            header("Location: index.php?page=appointment&error=closed");
            exit;
        }

        // strtotime() sait comparer des heures ("09:00" et "09:00:00")
        // sans se soucier du format exact, du moment qu'on les colle
        // a la meme date bidon ("1970-01-01") avant de les comparer.
        // C'est plus fiable qu'une comparaison directe de chaines de
        // caracteres, qui marcherait par chance ici mais pas toujours.
        $heureChoisie = strtotime("1970-01-01 " . $heure);
        $heureOuverture = strtotime("1970-01-01 " . $horaire->getHeureOuverture());
        $heureFermeture = strtotime("1970-01-01 " . $horaire->getHeureFermeture());

        // On verifie aussi que la consultation a le temps de se
        // terminer avant la fermeture, en ajoutant la duree du service
        // (en minutes) a l'heure choisie - sinon un rendez-vous de
        // 17h50 pourrait deborder sur la fermeture de 18h.
        $serviceManager = new ServiceManager();
        $service = $serviceManager->getService((int)$idService);
        $heureFin = $service ? $heureChoisie + ($service->getDuree() * 60) : $heureChoisie;

        if ($heureChoisie < $heureOuverture || $heureFin > $heureFermeture) {
            header("Location: index.php?page=appointment&error=hours");
            exit;
        }

        $rendezVousManager = new RendezVousManager();
        $rendezVousManager->createRendezVous(
            $_SESSION["patient_id"],
            (int)$idService,
            $date,
            $heure
        );
        header("Location: index.php?page=home&success=true");
        exit;
    }
}
