<?php
// require_once 'Database.php';

class Contrat {
    private $pdo;

    public function __construct(Database $database)
    {
         $this->pdo = $database->getConnection();
    }

    // Add a new contrat
    public function addContrat($dateDebut, $dateFin, $dure, $cinClient, $idMatric) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO contrats (date_debut, date_fin, dure, cin_client, id_matric) 
                VALUES (:date_debut, :date_fin, :dure, :cin_client, :id_matric)
            ");
            $stmt->execute([
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'dure' => $dure,
                'cin_client' => $cinClient,
                'id_matric' => $idMatric
            ]);
            return "Contrat added successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
    // Get all contrat details with joined data
    public function getAllContratDetails() {
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    contrats.id,
                    contrats.date_debut,
                    contrats.date_fin,
                    contrats.dure,
                    contrats.cin_client AS cin,
                    clients.nom,
                    clients.adress,
                    voitures.matricule,
                    voitures.marque,
                    voitures.modele,
                    voitures.annee
                FROM contrats
                JOIN clients ON clients.cin = contrats.cin_client
                JOIN voitures ON voitures.matricule = contrats.id_matric
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
    // Get all contrats
    public function getAllContrats() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM contrats");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Get contrat by ID
    public function getContratById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM contrats WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Update a contrat
    public function updateContrat($id, $dateDebut, $dateFin, $dure, $cinClient, $idMatric) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE contrats 
                SET date_debut = :date_debut, date_fin = :date_fin, dure = :dure, 
                    cin_client = :cin_client, id_matric = :id_matric
                WHERE id = :id
            ");
            $stmt->execute([
                'id' => $id,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'dure' => $dure,
                'cin_client' => $cinClient,
                'id_matric' => $idMatric
            ]);
            return "Contrat updated successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Delete a contrat
    public function deleteContrat($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM contrats WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return "Contrat deleted successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
