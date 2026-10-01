<?php

// une seule "fiche" texte, contrairement a Service
// ou Actualite qui existent en plusieurs exemplaires. On garde quand
// meme la separation Model/Manager pour rester cohesent avec le reste
// du projet (meme s'il n'y aura jamais qu'une seule ligne en base).
class APropos {

    private int $id;
    private string $contenu;
    private string $dateModification;

    public function __construct(array $data) {
        $this->id = $data["id"];
        $this->contenu = $data["contenu"];
        $this->dateModification = $data["date_modification"] ?? "";
    }

    public function getId(): int {
        return $this->id;
    }

    public function getContenu(): string {
        return $this->contenu;
    }

    public function getDateModification(): string {
        return $this->dateModification;
    }
}
