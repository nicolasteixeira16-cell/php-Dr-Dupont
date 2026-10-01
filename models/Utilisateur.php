<?php

class Utilisateur {

    private int $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private string $role;

    public function __construct(array $data) {
        $this->id = $data["id"];
        $this->nom = $data["nom"];
        $this->prenom = $data["prenom"];
        $this->email = $data["email"];
        $this->password = $data["password"];
        $this->role = $data["role"] ?? "assistant";
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNomComplet(): string {
        return $this->prenom . " " . $this->nom;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRole(): string {
        return $this->role;
    }
}
