<?php

require_once 'models/Accueil.php';

class AccueilManager {

    private function connectDB(): PDO {
        $pdo = new PDO("mysql:host=localhost;dbname=cabinet_dupont;charset=utf8", "root", "");
        $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    // Identique a AProposManager::getAPropos() : il n'y a jamais
    // qu'UNE seule ligne (id = 1), creee une fois pour toutes dans
    // phpMyAdmin.
    public function getAccueil(): Accueil | null {
        $pdo = $this->connectDB();
        $request = $pdo->query("SELECT * FROM accueil LIMIT 1");
        $ligne = $request->fetch();
        if (!$ligne) {
            return null;
        }
        return new Accueil($ligne);
    }

    // Equivalent de AProposManager::updateAPropos(), avec un champ
    // image en plus. $image peut valoir null : c'est le controleur qui
    // decide de garder l'ancienne image si aucune nouvelle n'est envoyee
    // (meme "reflexe image" que pour les actualites).
    public function updateAccueil(string $titre, string $texte, ?string $image): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare(
            "UPDATE accueil SET titre = ?, texte = ?, image = ? WHERE id = 1"
        );
        $request->execute([$titre, $texte, $image]);
    }
}
