<?php

// Fichier "helper" : un ensemble de fonctions reutilisables, sans classe,
// regroupees ici parce qu'elles servent toutes a la meme chose (verifier
// qui est connecte) et etaient avant recopiees-collees au debut de
// chaque methode de PatientController et AdminController.
// C'est exactement le meme controle qu'avant (isset() + redirection +
// exit), seulement ecrit UNE FOIS ici et appele partout ailleurs.

// A appeler en tout DEBUT d'une methode reservee a un patient connecte.
// Si personne n'est connecte, on redirige vers la connexion et on
// arrete immediatement l'execution (exit) : le reste de la methode
// (qui suppose un patient connecte) ne s'execute jamais dans ce cas.
function requirePatientAuth(): void {
    if (!isset($_SESSION["patient_id"])) {
        header("Location: index.php?page=login");
        exit;
    }
}

// Equivalent pour le personnel du cabinet (assistant ou administrateur),
// avec sa propre cle de session "staff_id" et sa propre page de connexion.
function requireStaffAuth(): void {
    if (!isset($_SESSION["staff_id"])) {
        header("Location: index.php?page=admin-login");
        exit;
    }
}

// Verification plus stricte, reservee au Dr. Dupont (role "administrateur") :
// par exemple pour creer de nouveaux acces au personnel. On commence
// TOUJOURS par requireStaffAuth(), pour etre sur qu'un staff_role existe
// bien en session avant de le comparer (sinon un simple visiteur non
// connecte pourrait, dans certains cas, passer le test par accident).
function requireAdminAuth(): void {
    requireStaffAuth();
    if (($_SESSION["staff_role"] ?? "") !== "administrateur") {
        header("Location: index.php?page=admin&error=forbidden");
        exit;
    }
}
