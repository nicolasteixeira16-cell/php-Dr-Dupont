<?php

require_once 'models/APropos.php';

class AProposManager {

    private function connectDB(): PDO {
        $pdo = new PDO("mysql:host=localhost;dbname=cabinet_dupont;charset=utf8", "root", "");
        $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    // Il n'y a jamais qu'UNE seule ligne dans la table a_propos (id = 1,
    // creee une fois pour toutes dans phpMyAdmin). On la recupere donc
    // avec une simple requete "LIMIT 1", sans avoir besoin d'un id
    // en parametre - contrairement a getService(int $id) par exemple.
    public function getAPropos(): APropos | null {
        $pdo = $this->connectDB();
        $request = $pdo->query("SELECT * FROM a_propos LIMIT 1");
        $ligne = $request->fetch();

        if (!$ligne) {
            return null;
        }
        return new APropos($ligne);
    }

    // Equivalent de updateStatut() dans RendezVousManager : on modifie
    // un seul champ (le contenu) d'une ligne deja existante, on ne fait
    // jamais d'INSERT ici puisque la ligne existe toujours deja.
    public function updateAPropos(string $contenu): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare(
            "UPDATE a_propos SET contenu = ?, date_modification = NOW() WHERE id = 1"
        );
        $request->execute([$contenu]);
    }
}
