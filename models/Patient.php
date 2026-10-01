<?php

class Patient {

    private int $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $telephone;
    private string $password;

    public function __construct(array $data) {
        $this->id = $data["id"];
        $this->nom = $data["nom"];
        $this->prenom = $data["prenom"];
        $this->email = $data["email"];
        $this->telephone = $data["telephone"] ?? "";
        $this->password = $data["password"];
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getPrenom(): string {
        return $this->prenom;
    }

    // combine prenom + nom en une seule chaine, utile pour l'affichage
    // (ex: dans la liste des rendez-vous cote admin).
    public function getNomComplet(): string {
        return $this->prenom . " " . $this->nom;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getTelephone(): string {
        return $this->telephone;
    }

    public function getPassword(): string {
        return $this->password;
    }
}
