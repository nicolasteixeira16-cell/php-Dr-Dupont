<?php

require_once 'models/RendezVous.php';

class RendezVousManager {

    private function connectDB(): PDO {
        $pdo = new PDO("mysql:host=localhost;dbname=cabinet_dupont;charset=utf8", "root", "");
        $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

   
    // on fait deux jointures (patients ET services), pour tout recuperer
    // en une seule requete plutot que d'interroger la base 3 fois.
    public function getAllRendezVous(): array {
        $pdo = $this->connectDB();

        $request = $pdo->query(
            "SELECT rendez_vous.*, 
                    patients.nom AS patient_nom, patients.prenom AS patient_prenom,
                    services.nom AS service_nom
             FROM rendez_vous
             LEFT JOIN patients ON rendez_vous.id_patient = patients.id
             LEFT JOIN services ON rendez_vous.id_service = services.id
             ORDER BY rendez_vous.date, rendez_vous.heure"
        );

        $lignes = $request->fetchAll();
        $rendezVous = [];
        foreach ($lignes as $ligne) {
            // On fusionne prenom + nom en une seule cle "patient_nom"
            // pour que le constructeur de RendezVous les recupere
            // directement (voir Patient::getNomComplet()).
            $ligne["patient_nom"] = ($ligne["patient_prenom"] ?? "") . " " . ($ligne["patient_nom"] ?? "");
            $rendezVous[] = new RendezVous($ligne);
        }
        return $rendezVous;
    }

    // Equivalent de getAllRendezVous(), mais filtre "WHERE id_patient = ?"
    // pour qu'un patient connecte ne voie QUE ses propres rendez-vous
    // (jamais ceux des autres patients). Jointure avec services
    // uniquement : le patient sait deja que c'est lui, inutile de
    // rejoindre la table patients ici.
    public function getRendezVousByPatient(int $idPatient): array {
        $pdo = $this->connectDB();

        $request = $pdo->prepare(
            "SELECT rendez_vous.*, services.nom AS service_nom
             FROM rendez_vous
             LEFT JOIN services ON rendez_vous.id_service = services.id
             WHERE rendez_vous.id_patient = ?
             ORDER BY rendez_vous.date, rendez_vous.heure"
        );
        $request->execute([$idPatient]);

        $lignes = $request->fetchAll();
        $rendezVous = [];
        foreach ($lignes as $ligne) {
            $rendezVous[] = new RendezVous($ligne);
        }
        return $rendezVous;
    }


    public function getRendezVous(int $id): RendezVous | null {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("SELECT * FROM rendez_vous WHERE id = ?");
        $request->execute([$id]);
        $ligne = $request->fetch();

        if (!$ligne) {
            return null;
        }
        return new RendezVous($ligne);
    }

    // un rendez-vous est toujours lie a UN patient et UN service
    // (relation simple), pas besoin de boucle d'insertion supplementaire.
    public function createRendezVous(int $idPatient, int $idService, string $date, string $heure): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare(
            "INSERT INTO rendez_vous (id_patient, id_service, date, heure, statut, date_creation)
             VALUES (?, ?, ?, ?, 'en_attente', NOW())"
        );
        $request->execute([$idPatient, $idService, $date, $heure]);
    }

    // Equivalent de updateService()/updatePatient() : on modifie les
    // champs "reservation" d'un rendez-vous (service/date/heure), sans
    // toucher au statut - c'est updateStatut() ci-dessous qui s'en charge.
    // Les deux methodes existent separement car ce sont deux actions
    // differentes pour l'utilisateur (deplacer un rendez-vous n'est pas
    // pareil que le confirmer ou l'annuler).
    public function updateRendezVous(int $id, int $idService, string $date, string $heure): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare(
            "UPDATE rendez_vous SET id_service = ?, date = ?, heure = ? WHERE id = ?"
        );
        $request->execute([$idService, $date, $heure, $id]);
    }

   
    public function updateStatut(int $id, string $statut): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("UPDATE rendez_vous SET statut = ? WHERE id = ?");
        $request->execute([$statut, $id]);
    }

   
    public function deleteRendezVous(int $id): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("DELETE FROM rendez_vous WHERE id = ?");
        $request->execute([$id]);
    }
}
