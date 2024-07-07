<?php

require_once 'config.php';

function connectToDatabase()
{
  $retry = 0;
  while ($retry < 1) {
    //echo "Retry #{$retry}\n";
    try {
      $conn = new PDO("mysql:host=localhost;port=3306;dbname=" . DB_NAME, DB_USER, DB_PASS);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //echo " Connected successfully\n";
      return $conn;
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage() . ". Retrying in 5 seconds...\n";
      sleep(5);
      $retry++;
    }
  }
  return null;
}
function loadCsvFileAndInsertCategories($conn)
{
  if (($handle = fopen(CSV_FILE, 'r')) !== FALSE) {
    $categories = array();
    fgetcsv($handle, 1000, ','); // Skip the first line (header row)
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $categories = array_merge($categories, explode(',', $data[10])); // Split categories by comma and merge with existing array
    }
    fclose($handle);

    // Remove duplicates and sort the categories array
    $categories = array_unique($categories);
    sort($categories);

    foreach ($categories as $category) {
      $category = trim($category);
      if (!empty($category)) {
        // Check if the category already exists in the database
        $sql = "SELECT id FROM categories WHERE name = :category";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        $category_id = $stmt->fetchColumn();

        if (!$category_id) { // If the category doesn't exist, insert it
          $sql = "INSERT INTO categories (name) VALUES (:category)";
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(':category', $category);
          try {
            $stmt->execute();
            //echo "Category '$category' inserted successfully!\n";
          } catch (PDOException $e) {
            error_log("Error inserting category '$category': " . $e->getMessage());
          }
        } else {
          //echo "Category '$category' already exists in the database, skipping...\n";
        }
      }
    }
  }
}

function loadCsvFileAndInsertMovies($conn)
{
  if (($handle = fopen(CSV_FILE, 'r')) !== FALSE) {
    fgetcsv($handle, 1000, ','); // Skip the first line (header row)
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $title = trim($data[2]);
      $categories = trim($data[10]); // Assuming categories are in the 4th column
      $categoryArray = explode(',', $categories); // Split categories by comma
      $categoryName = trim($categoryArray[0]); // Take the first category

      // Get the category ID from the categories table
      $sql = "SELECT id FROM categories WHERE name = :categoryName";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':categoryName', $categoryName);
      $stmt->execute();
      $categoryId = $stmt->fetchColumn();

      if (!$categoryId) {
        error_log("Category '$categoryName' not found, skipping movie '$title'");
        continue;
      }

      // Check if the title already exists in the database
      $sql = "SELECT COUNT(*) AS count FROM movie WHERE title = :title";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':title', $title);
      $stmt->execute();
      $count = $stmt->fetchColumn();

      if ($count == 0) { // If the title doesn't exist, insert it
        $sql = "INSERT INTO movie (title, category_id) VALUES (:title, :categoryId)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':categoryId', $categoryId);
        try {
          $stmt->execute();
          //echo "Movie '$title' inserted successfully with category ID $categoryId!\n";
        } catch (PDOException $e) {
          error_log("Error inserting movie '$title': " . $e->getMessage());
        }
      } else {
        //echo "Movie '$title' already exists in the database, skipping\n";
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
