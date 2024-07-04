<?php

// Define constants
define('SERVER_NAME', 'localhost');
define('DB_NAME', 'movies');
define('DB_USER', 'root');
define('DB_PASS', 'pass');
define('CSV_FILE', 'netflix_titles.csv'); // path to your CSV file

// Function to connect to database
function connectToDatabase()
{
  $retry = 0;
  while ($retry < 10) {
    echo "Retry #{$retry}\n";
    try {
      $conn = new PDO("mysql:host=" . SERVER_NAME . ";port=3306;dbname=" . DB_NAME, DB_USER, DB_PASS);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo "Connected successfully\n";
      return $conn;
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage() . ". Retrying in 5 seconds...\n";
      sleep(5);
      $retry++;
    }
  }
  return null;
}

function getCsvData($conn)
{
  $sql = "SELECT m.movie_id AS id, m.title, c.name AS category
          FROM movie m
          JOIN categories c ON (m.category_id = c.id)
          ORDER BY m.movie_id";

  $stmt = $conn->prepare($sql);
  try {
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results;
  } catch (PDOException $e) {
    echo "Error getting CSV data: " . $e->getMessage() . "\n";
    return array();
  }
}

//create a new script / route which will return the csv file in json ... its enough to include: id,title, category ...  you may add new fields if you wish 
$conn = connectToDatabase();
if ($conn) {
  $csvData = getCsvData($conn);
  $json = json_encode($csvData, JSON_PRETTY_PRINT);
  header('Content-Type: application/json');
  echo $json;
}