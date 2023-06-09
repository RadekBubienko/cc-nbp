<?php

//pobieranie 

require_once "./helpers/connect.php";

class GetDataFromApiNBP
{
  private $apiUrl;
  private $conn;

  public function __construct($apiUrl, $connection)
  {
    $this->apiUrl = $apiUrl;
    $this->conn = $connection;
  }

  public function getData()
  {
    $response = file_get_contents($this->apiUrl);
    if ($response === false) {
      die("Błąd pobierania kursów z bazy NBP");
    }

    $data = json_decode($response, true);
    return $data;
  }

  public function saveDataToDatabase($data)
  {
    $tableName = "currency_tables";

    $no = $data[0]['no'];
    $tableData = [
      'table_name' => $data[0]['table'],
      'no' => $no,
      'effective_date' => $data[0]['effectiveDate'],
      'rates' => json_encode($data[0]['rates'], JSON_UNESCAPED_UNICODE)
    ];

    $fields = implode(", ", array_keys($tableData));
    $values = "'" . implode("', '", array_values($tableData)) . "'";

    $query = "INSERT INTO $tableName ($fields) VALUES ($values) 
              ON DUPLICATE KEY UPDATE 
              table_name = VALUES(table_name), 
              effective_date = VALUES(effective_date), 
              rates = VALUES(rates)";

    $this->conn->query($query);
  }

  public function getConnection()
  {
    return $this->conn;
  }
}

$nbpApi = new GetDataFromApiNBP(
  "https://api.nbp.pl/api/exchangerates/tables/a/?format=json",
  $conn
);

$data = $nbpApi->getData();
$currencyName = "polski złoty";
$currency = json_encode($currencyName, JSON_UNESCAPED_UNICODE);
$rate = array("currency" => $currencyName, "code" => "PLN", "mid" => 1.0000);
array_unshift($data[0]["rates"], $rate);
$nbpApi->saveDataToDatabase($data);
