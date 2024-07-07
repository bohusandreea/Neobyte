<?php

require_once 'config.php';
require_once 'functions.php';
define('CSV_FILE', 'netflix_titles.csv');

$conn = connectToDatabase();
if ($conn) {
  $csvData = getCsvData($conn);
  $json = json_encode($csvData, JSON_PRETTY_PRINT);
  header('Content-Type: application/json');
  echo $json;
}