<?php

class Actualite {

    private int $id;
    private string $titre;
    private string $contenu;
    private ?string $image;
    private string $date;

    public function __construct(array $data) {
        $this->id = $data["id"];
        $this->titre = $data["titre"];
        $this->contenu = $data["contenu"];
        $this->image = $data["image"] ?? null;
        $this->date = $data["date_creation"];
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getContenu(): string {
        return $this->contenu;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    public function getDate(): string {
        return $this->date;
    }
}
