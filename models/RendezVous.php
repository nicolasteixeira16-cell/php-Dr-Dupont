<?php

class RendezVous {

    private int $id;
    private int $idPatient;
    private int $idService;
    private string $date;
    private string $heure;
    private string $statut;
    private string $dateCreation;

    // Ces deux-la sont optionnels (?string), ils n'existent que si
    // la requete SQL a fait une JOINTURE avec patients/services.
    private ?string $patientNom;
    private ?string $serviceNom;

    public function __construct(array $data) {
        $this->id = $data["id"];
        $this->idPatient = $data["id_patient"];
        $this->idService = $data["id_service"];
        $this->date = $data["date"];
        $this->heure = $data["heure"];
        $this->statut = $data["statut"];
        $this->dateCreation = $data["date_creation"] ?? "";

        // Si la requete a fait un JOIN, ces cles existent.
        // Sinon (ex: getRendezVous simple sans jointure), elles
        // seront absentes et on met null a la place - 
        $this->patientNom = $data["patient_nom"] ?? null;
        $this->serviceNom = $data["service_nom"] ?? null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getIdPatient(): int {
        return $this->idPatient;
    }

    public function getIdService(): int {
        return $this->idService;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function getHeure(): string {
        return $this->heure;
    }

    public function getStatut(): string {
        return $this->statut;
    }

    public function getPatientNom(): ?string {
        return $this->patientNom;
    }

    public function getServiceNom(): ?string {
        return $this->serviceNom;
    }
}
