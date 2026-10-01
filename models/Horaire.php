<?php

class Horaire {

    private string $jour;
    private ?string $heureOuverture;
    private ?string $heureFermeture;
    private bool $ferme;

    public function __construct(array $data) {
        $this->jour = $data["jour"];
        $this->heureOuverture = $data["heure_ouverture"] ?? null;
        $this->heureFermeture = $data["heure_fermeture"] ?? null;
        // En base, "ferme" est stocke comme un TINYINT (0 ou 1).
        // On le convertit tout de suite en vrai booleen PHP, pour
        // pouvoir ecrire "if ($horaire->estFerme())" plutot que
        // "if ($horaire->getFerme() == 1)" dans les vues.
        $this->ferme = (bool)($data["ferme"] ?? false);
    }

    public function getJour(): string {
        return $this->jour;
    }

    // Nullable exactement pour la meme raison qu'on en avait discute :
    // un jour ferme n'a pas d'heure d'ouverture/fermeture a proprement
    // parler, donc la valeur en base (et ici) peut etre NULL.
    public function getHeureOuverture(): ?string {
        return $this->heureOuverture;
    }

    public function getHeureFermeture(): ?string {
        return $this->heureFermeture;
    }

    public function estFerme(): bool {
        return $this->ferme;
    }

    // Petit helper pour l'affichage cote front-office : evite de
    // repeter la meme condition dans chaque vue qui affiche les horaires.
    public function getLibelleJour(): string {
        $libelles = [
            "lundi" => "Lundi",
            "mardi" => "Mardi",
            "mercredi" => "Mercredi",
            "jeudi" => "Jeudi",
            "vendredi" => "Vendredi",
            "samedi" => "Samedi",
            "dimanche" => "Dimanche",
        ];
        return $libelles[$this->jour] ?? $this->jour;
    }
}
