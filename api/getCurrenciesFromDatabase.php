<?php

//pobieranie aktualnej tabeli NBP z bazy danych

require_once "./helpers/connect.php";

class GetCurrenciesFromDatabase {
  
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function getCurrencies()
  {
    try {
      $query = "SELECT * FROM currency_tables WHERE id=(SELECT max(id) FROM currency_tables LIMIT 1)";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result;
    } catch (PDOException $e) {
      echo "Błąd pobierania danych z bazy danych: " . $e->getMessage();
      return [];
    }
  }
}