<?php

require_once 'models/Patient.php';

class PatientManager {


    private function connectDB(): PDO {
        $pdo = new PDO("mysql:host=localhost;dbname=cabinet_dupont;charset=utf8", "root", "");
        $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public function emailAlreadyExists(string $email): bool {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("SELECT * FROM patients WHERE email = ?");
        $request->execute([$email]);
        $patient = $request->fetch();
        return $patient ? true : false;
    }


    public function register(string $nom, string $prenom, string $email, string $telephone, string $password): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("INSERT INTO patients (nom, prenom, email, telephone, password, date_creation) VALUES (?, ?, ?, ?, ?, NOW())");
        $request->execute([$nom, $prenom, $email, $telephone, $password]);
    }


    public function getPatient(string $email): Patient | null {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("SELECT * FROM patients WHERE email = ?");
        $request->execute([$email]);
        $patient = $request->fetch();
        if ($patient) {
            return new Patient($patient);
        }
        return null;
    }

    
    public function getPatients(): array {
        $pdo = $this->connectDB();
        $request = $pdo->query("SELECT * FROM patients ORDER BY nom");
        $patients = [];
        foreach ($request->fetchAll() as $ligne) {
            $patients[] = new Patient($ligne);
        }
        return $patients;
    }

   
    public function getPatientById(int $id): Patient | null {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
        $request->execute([$id]);
        $patient = $request->fetch();
        if ($patient) {
            return new Patient($patient);
        }
        return null;
    }

    
    public function updatePatient(int $id, string $nom, string $prenom, string $email, string $telephone): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare(
            "UPDATE patients SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id = ?"
        );
        $request->execute([$nom, $prenom, $email, $telephone, $id]);
    }

   
    public function deletePatient(int $id): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("DELETE FROM patients WHERE id = ?");
        $request->execute([$id]);
    }
}
