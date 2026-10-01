<?php

require_once 'helpers/auth.php';
require_once 'models/PatientManager.php';
require_once 'models/RendezVousManager.php';

class PatientController {

    public function register() {
        require 'views/register.php';
    }

    // Equivalent d'un "adminAppointments()" mais cote patient. La
    // verification "est-ce que je suis connecte ?" est maintenant
    // centralisee dans helpers/auth.php au lieu d'etre recopiee ici.
    public function myAppointments() {
        requirePatientAuth();

        $rendezVousManager = new RendezVousManager();
        $rendezVous = $rendezVousManager->getRendezVousByPatient($_SESSION["patient_id"]);

        require 'views/my-appointments.php';
    }

    public function login() {
        require 'views/login.php';
    }


    public function loginValid() {
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        $patientManager = new PatientManager();

        if (!$email || !$password) {
            header("Location: index.php?page=login&error=fields");
            exit;
        }

        $patient = $patientManager->getPatient($email);

        if (!$patient) {
            // Email inconnu en base.
            header("Location: index.php?page=login&error=credentials");
            exit;
        }

        if (!password_verify($password, $patient->getPassword())) {
            // Email trouve, mais mot de passe incorrect.
            header("Location: index.php?page=login&error=credentials");
            exit;
        }

        // Tout est bon : on ouvre la session du patient.
        $_SESSION["patient_id"] = $patient->getId();
        $_SESSION["patient_name"] = $patient->getNomComplet();
        header("Location: index.php?page=home");
        exit;
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header("Location: index.php?page=home");
        exit;
    }

   
    public function registerValid() {
        $nom = trim($_POST["nom"] ?? "");
        $prenom = trim($_POST["prenom"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $telephone = trim($_POST["telephone"] ?? "");
        $password = $_POST["password"] ?? "";

        $patientManager = new PatientManager();

        if (!$nom || !$prenom || !$email || !$password) {
            header("Location: index.php?page=register&error=fields");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: index.php?page=register&error=email-format");
            exit;
        }

        if ($patientManager->emailAlreadyExists($email)) {
            header("Location: index.php?page=register&error=email-used");
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $patientManager->register($nom, $prenom, $email, $telephone, $passwordHash);
        header("Location: index.php?page=login");
        exit;
    }
}
