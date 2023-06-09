<?php

//pobieranie zapisanych kalkulacji z bazy danych

require_once "./helpers/connect.php";

class GetCalculationsFromDatabase {
  
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function getCalculations()
  {
    try {
      $query = "SELECT * FROM calculations ORDER BY id DESC LIMIT 20";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();

      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $result;
    } catch (PDOException $e) {
      echo "Błąd pobierania danych z bazy danych: " . $e->getMessage();
      return [];
    }
  }
}
