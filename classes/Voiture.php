<?php


require_once __DIR__ . '/Database.php';

class Voiture {
    private $pdo;

    public function __construct(Database $database) {
        $this->pdo = $database->getConnection();
    }

    // Create a new voiture
    public function create($matricule, $marque, $modele, $annee) {
        $sql = "INSERT INTO voitures (matricule, marque, modele, annee) VALUES (:matricule, :marque, :modele, :annee)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['matricule' => $matricule, 'marque' => $marque, 'modele' => $modele, 'annee' => $annee]);
        return $stmt->rowCount() > 0;
    }

    // Read a voiture by matricule
    public function read($matricule) {
        $sql = "SELECT * FROM voitures WHERE matricule = :matricule";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['matricule' => $matricule]);
        return $stmt->fetch();
    }

    // Update an existing voiture
    public function update($matricule, $marque, $modele, $annee) {
        $sql = "UPDATE voitures SET marque = :marque, modele = :modele, annee = :annee WHERE matricule = :matricule";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['matricule' => $matricule, 'marque' => $marque, 'modele' => $modele, 'annee' => $annee]);
    }

    // Delete a voiture by matricule
    public function delete($matricule) {
        $sql = "DELETE FROM voitures WHERE matricule = :matricule";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['matricule' => $matricule]);
    }

    // Get all voitures
    public function getAll() {
        $sql = "SELECT * FROM voitures";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
