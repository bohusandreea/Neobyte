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

// Function to load CSV file and insert categories into database
function loadCsvFileAndInsertCategories($conn)
{
  if (($handle = fopen(CSV_FILE, 'r')) !== FALSE) {
    $categories = array();
    fgetcsv($handle, 1000, ','); // Skip the first line (header row)
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $category = trim(explode(',', $data[10])[0]);
      if (!empty($category) && !in_array($category, $categories)) {
        $categories[] = $category;
      }
    }
    fclose($handle);

    foreach ($categories as $category) {
      $sql = "INSERT INTO categories (name) VALUES (:category)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':category', $category);
      try {
        $stmt->execute();
        echo "Category '$category' inserted successfully!\n";
      } catch (PDOException $e) {
        echo "Error inserting category '$category': " . $e->getMessage() . "\n";
      }
    }
  }
}

// Function to load CSV file and insert movies into database
function loadCsvFileAndInsertMovies($conn)
{
  if (($handle = fopen(CSV_FILE, 'r')) !== FALSE) {
    fgetcsv($handle, 1000, ','); // Skip the first line (header row)
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $title = trim($data[2]);
      $categories = explode(',', $data[10]); // Split categories by comma
      $category_name = trim($categories[0]); // Take only the first category

      // Retrieve the category ID from the categories table
      $sql = "SELECT id FROM categories WHERE name = :category_name";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':category_name', $category_name);
      $stmt->execute();
      $category_id = $stmt->fetchColumn();

      // Insert the movie title into the movies table with the category ID
      $sql = "INSERT INTO movie (title, category_id) VALUES (:title, :category_id)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':category_id', $category_id);
      try {
        $stmt->execute();
        echo "Movie '$title' inserted successfully!\n";
      } catch (PDOException $e) {
        echo "Error inserting movie '$title': " . $e->getMessage() . "\n";
      }
    }
    fclose($handle);
  }
}

// Function to get movies with pagination
function getMoviesWithPagination($conn, $offset, $limit)
{
  $sql = "SELECT m.movie_id AS id, m.title, c.name AS category
          FROM movie m
          JOIN categories c ON (m.category_id = c.id)
          ORDER BY m.movie_id
          LIMIT :limit OFFSET :offset";

  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  try {
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results;
  } catch (PDOException $e) {
    echo "Error getting movies: " . $e->getMessage() . "\n";
    return array();
  }
}


$conn = connectToDatabase();
if ($conn) {
  // Load CSV file and insert categories into database

  loadCsvFileAndInsertCategories($conn);

  // Load CSV file and insert movies into database

  loadCsvFileAndInsertMovies($conn);

  // Get movies with pagination
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
  echo json_encode($json_data);
  $conn = null;

}
