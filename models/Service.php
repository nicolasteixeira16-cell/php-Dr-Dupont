<?php

class Service {

    private int $id;
    private string $nom;
    private string $description;
    private int $duree;
    private float $prix;

    public function __construct(array $data) {
        $this->id = $data["id"];
        $this->nom = $data["nom"];
        $this->description = $data["description"] ?? "";
        $this->duree = (int)$data["duree"];
        $this->prix = (float)$data["prix"];
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getDuree(): int {
        return $this->duree;
    }

    public function getPrix(): float {
        return $this->prix;
    }
}
