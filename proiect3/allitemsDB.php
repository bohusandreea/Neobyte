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

function GetAllItemsFromDB($conn)
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
    echo "Error getting movies: " . $e->getMessage() . "\n";
    return array();
  }
}

// Connect to database
$conn = connectToDatabase();

if ($conn) {
  $results = GetAllItemsFromDB($conn);
  // Output the results in a table format
  echo "<table border='1'>";
  echo "<tr><th>ID</th><th>Title</th><th>Category</th></tr>";
  foreach ($results as $row) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['title'] . "</td>";
    echo "<td>" . $row['category'] . "</td>";
    echo "</tr>";
  }
  echo "</table>";
} else {
  echo "Failed to connect to database";
}

// Close the database connection
$conn = null;
?>