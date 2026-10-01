<?php

// Meme principe que APropos.php : une seule "fiche" (id = 1), pour le
// contenu du gros encart de la page d'accueil (titre, texte, image).
class Accueil {

    private int $id;
    private string $titre;
    private string $texte;
    private ?string $image;

    public function __construct(array $data) {
        $this->id = $data["id"];
        $this->titre = $data["titre"];
        $this->texte = $data["texte"];
        $this->image = $data["image"] ?? null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getTexte(): string {
        return $this->texte;
    }

    // Nullable : au tout debut, avant que le Dr. Dupont n'uploade sa
    // propre photo, il n'y a pas encore d'image personnalisee - la vue
    // affichera alors une image par defaut a la place (voir home.php).
    public function getImage(): ?string {
        return $this->image;
    }
}
