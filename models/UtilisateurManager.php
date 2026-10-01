<?php

require_once 'models/Utilisateur.php';

class UtilisateurManager {

    private function connectDB(): PDO {
        $pdo = new PDO("mysql:host=localhost;dbname=cabinet_dupont;charset=utf8", "root", "");
        $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    // Contrairement au premier compte (Dr. Dupont), cree a la main dans
    // phpMyAdmin, les comptes suivants peuvent etre crees
    // depuis le back-office lui-meme (voir AdminController::createStaffValid()),
    // mais seulement par quelqu'un dont le role est "administrateur".
    public function getUtilisateur(string $email): Utilisateur | null {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $request->execute([$email]);
        $utilisateur = $request->fetch();
        if ($utilisateur) {
            return new Utilisateur($utilisateur);
        }
        return null;
    }

    // la liste complete pour l'affichage du tableau du personnel dans le back-office.
    public function getUtilisateurs(): array {
        $pdo = $this->connectDB();
        $request = $pdo->query("SELECT * FROM utilisateurs ORDER BY nom");
        $utilisateurs = [];
        foreach ($request->fetchAll() as $ligne) {
            $utilisateurs[] = new Utilisateur($ligne);
        }
        return $utilisateurs;
    }

    // Equivalent de PatientManager::emailAlreadyExists() : evite de
    // creer deux comptes staff avec le meme email.
    public function emailAlreadyExists(string $email): bool {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $request->execute([$email]);
        return $request->fetch() !== false;
    }

    // Equivalent de PatientManager::register(), mais avec un champ
    // "role" en plus (administrateur ou assistant) puisqu'un membre
    // du personnel n'a pas tous les memes droits qu'un autre.
    public function createUtilisateur(string $nom, string $prenom, string $email, string $password, string $role): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare(
            "INSERT INTO utilisateurs (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?)"
        );
        $request->execute([$nom, $prenom, $email, $password, $role]);
    }
}
