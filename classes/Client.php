<?php
// require_once 'Database.php';

class Client
{
     private $pdo;

     public function __construct(Database $database)
     {
          $this->pdo = $database->getConnection();
     }

     public function create($cin, $nom, $adress, $tel)
     {
          $sql = "INSERT INTO clients (cin, nom, adress, tel) VALUES (:cin, :nom, :adress, :tel)";
          $stmt = $this->pdo->prepare($sql);
          return $stmt->execute([
               'cin' => $cin,
               'nom' => $nom,
               'adress' => $adress,
               'tel' => $tel
          ]);
     }

     public function getByCIN($cin)
     {
          $sql = "SELECT * FROM clients WHERE cin = :cin";
          $stmt = $this->pdo->prepare($sql);
          $stmt->execute(['cin' => $cin]);
          return $stmt->fetch(PDO::FETCH_ASSOC);
     }

     public function update($cin, $nom, $adress, $tel)
     {
          $sql = "UPDATE clients SET nom = :nom, adress = :adress, tel = :tel WHERE cin = :cin";
          $stmt = $this->pdo->prepare($sql);
          return $stmt->execute([
               'cin' => $cin,
               'nom' => $nom,
               'adress' => $adress,
               'tel' => $tel
          ]);
     }

     public function delete($cin)
     {
          $sql = "DELETE FROM clients WHERE cin = :cin";
          $stmt = $this->pdo->prepare($sql);
          return $stmt->execute(['cin' => $cin]);
     }

     public function getAll()
     {
          $sql = "SELECT * FROM clients";
          $stmt = $this->pdo->query($sql);
          return $stmt->fetchAll(PDO::FETCH_ASSOC);
     }
}
