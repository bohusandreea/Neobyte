<?php

require_once 'config.php';
require_once 'functions.php';
define('CSV_FILE', 'netflix_titles.csv');

// Create a connection to the database
$conn = connectToDatabase();

if (!$conn) {
  echo "Connection failed: ";
  exit;
}

loadCsvFileAndInsertCategories($conn);

loadCsvFileAndInsertMovies($conn);


$conn = null;