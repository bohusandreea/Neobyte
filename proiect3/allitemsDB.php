<?php
require_once 'config.php';
require_once 'functions.php';
define('CSV_FILE', 'netflix_titles.csv');

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

