<?php

require_once 'models/Actualite.php';

class ActualiteManager {

    private function connectDB(): PDO {
        $pdo = new PDO("mysql:host=localhost;dbname=cabinet_dupont;charset=utf8", "root", "");
        $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    // ici, car les actualites ne sont pas rattachees a un parent,
    // elles s'affichent toutes ensemble sans jointure.
    public function getActualites(): array {
        $pdo = $this->connectDB();
        $request = $pdo->query("SELECT * FROM actualites ORDER BY date_creation DESC");

        $lignes = $request->fetchAll();
        $actualites = [];
        foreach ($lignes as $ligne) {
            $actualites[] = new Actualite($ligne);
        }
        return $actualites;
    }

    public function createActualite(string $titre, string $contenu, ?string $image, int $idAuteur): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare(
            "INSERT INTO actualites (titre, contenu, image, date_creation, id_auteur) VALUES (?, ?, ?, NOW(), ?)"
        );
        $request->execute([$titre, $contenu, $image, $idAuteur]);
    }

    // une seule actualite, pour
    // pre-remplir le formulaire de modification.
    public function getActualite(int $id): Actualite | null {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("SELECT * FROM actualites WHERE id = ?");
        $request->execute([$id]);
        $ligne = $request->fetch();
        if (!$ligne) {
            return null;
        }
        return new Actualite($ligne);
    }

    // si aucune nouvelle image n'est fournie ($image vaut
    // null), on garde l'image existante au lieu de l'effacer. C'est
    // le controleur (voir editNewsValid()) qui decide de cette valeur
    // avant d'appeler cette methode.
    public function updateActualite(int $id, string $titre, string $contenu, ?string $image): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare(
            "UPDATE actualites SET titre = ?, contenu = ?, image = ? WHERE id = ?"
        );
        $request->execute([$titre, $contenu, $image, $id]);
    }

    // L'image laissee dans uploads/ n'est pas supprimee du
    // disque (comme pour le blog) - ce n'est pas grave, juste un
    // fichier orphelin qui ne s'affiche plus nulle part.
    public function deleteActualite(int $id): void {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("DELETE FROM actualites WHERE id = ?");
        $request->execute([$id]);
    }
}
