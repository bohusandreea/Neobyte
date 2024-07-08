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

/*
$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

$results = getMoviesWithPagination($conn, $offset, $limit);
$json_data = array();
foreach ($results as $movie) {
  $json_data[] = array(
    'id' => $movie['id'],
    'title' => $movie['title'],
    'category' => $movie['category']
  );
}

header('Content-Type: application/json');
echo json_encode($json_data, JSON_PRETTY_PRINT);*/

$movies = GetAllItemsFromDB($conn);

$json_data = json_encode($movies, JSON_PRETTY_PRINT);

$json_array = json_decode($json_data, true);

$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

$json_data = json_encode(array_slice($json_array, $offset, $limit), JSON_PRETTY_PRINT);

header('Content-Type: application/json');
echo $json_data;

$conn = null;

// Close the database connection
$conn = null;