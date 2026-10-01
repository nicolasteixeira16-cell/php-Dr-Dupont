<?php

require_once 'models/Service.php';

class ServiceManager {

    private function connectDB(): PDO {
        $pdo = new PDO("mysql:host=localhost;dbname=cabinet_dupont;charset=utf8", "root", "");
        $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public function getServices(): array {
        $pdo = $this->connectDB();
        $request = $pdo->query("SELECT * FROM services");
        $services = [];
        foreach ($request->fetchAll() as $service) {
            $services[] = new Service($service);
        }
        return $services;
    }

    
    public function createService(string $nom, string $description, int $duree, float $prix): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("INSERT INTO services (nom, description, duree, prix) VALUES (?, ?, ?, ?)");
        $request->execute([$nom, $description, $duree, $prix]);
    }

    // Equivalent de getRendezVous(int $id) : recupere UN SEUL service
    // par son id, pour pre-remplir le formulaire de modification.
    public function getService(int $id): Service | null {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("SELECT * FROM services WHERE id = ?");
        $request->execute([$id]);
        $ligne = $request->fetch();
        if (!$ligne) {
            return null;
        }
        return new Service($ligne);
    }

    // on remplace TOUS les
    // champs modifiables d'un coup.
    public function updateService(int $id, string $nom, string $description, int $duree, float $prix): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare(
            "UPDATE services SET nom = ?, description = ?, duree = ?, prix = ? WHERE id = ?"
        );
        $request->execute([$nom, $description, $duree, $prix, $id]);
    }

    
    public function deleteService(int $id): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $request->execute([$id]);
    }
}
