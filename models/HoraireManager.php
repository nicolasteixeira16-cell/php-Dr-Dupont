<?php

require_once 'models/Horaire.php';

class HoraireManager {

    private function connectDB(): PDO {
        $pdo = new PDO("mysql:host=localhost;dbname=cabinet_dupont;charset=utf8", "root", "");
        $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    // Contrairement a getServices() ou getActualites(), l'ordre naturel
    // de la base (alphabetique ou par id) ne convient pas : on veut
    // toujours lundi -> dimanche. On force donc l'ordre avec un FIELD(),
    // l'equivalent SQL d'un ORDER BY "sur mesure".
    public function getHoraires(): array {
        $pdo = $this->connectDB();
        $request = $pdo->query(
            "SELECT * FROM horaires
             ORDER BY FIELD(jour, 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche')"
        );
        $horaires = [];
        foreach ($request->fetchAll() as $ligne) {
            $horaires[] = new Horaire($ligne);
        }
        return $horaires;
    }

    // Equivalent de getService(int $id)/getPatientById(int $id), mais
    // par "jour" plutot que par un id numerique : c'est la cle primaire
    // de cette table. Sert a verifier, au moment de la prise de
    // rendez-vous, si le jour choisi par le patient est ouvert ou non.
    public function getHoraireByJour(string $jour): Horaire | null {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("SELECT * FROM horaires WHERE jour = ?");
        $request->execute([$jour]);
        $ligne = $request->fetch();
        if (!$ligne) {
            return null;
        }
        return new Horaire($ligne);
    }

    // Equivalent de updateStatut() : on modifie une ligne qui existe
    // deja (les 7 jours sont crees une fois pour toutes dans phpMyAdmin),
    // jamais d'INSERT ici. $heureOuverture/$heureFermeture peuvent
    // valoir null quand $ferme est vrai - c'est le controleur qui
    // decide de cette coherence avant d'appeler cette methode.
    public function updateHoraire(string $jour, ?string $heureOuverture, ?string $heureFermeture, bool $ferme): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare(
            "UPDATE horaires SET heure_ouverture = ?, heure_fermeture = ?, ferme = ? WHERE jour = ?"
        );
        $request->execute([$heureOuverture, $heureFermeture, $ferme ? 1 : 0, $jour]);
    }
}
