# Movies Project

================

This project is a simple web application that displays a list of movies from a Netflix titles CSV file. The application allows users to paginate through the list of movies using offset and limit parameters (new version: retrieving all the data from the database -> json + limit + offset -> html table).

## Files

### config.php

Defines constants for database connection settings (server name, database name, username, password)

### allitemsDB.php

- Connects to the database using the `connectToDatabase()` function
- Retrieves all items from the database using the `GetAllItemsFromDB()` function
- Outputs the results in a table format

### csvtojson.php

- Connects to the database using the `connectToDatabase()` function
- Retrieves CSV data from the database using the `getCsvData()` function
- Converts the CSV data to JSON and outputs it

### index.html

- The main HTML file for the application
- Contains a form with input fields for offset and limit
- Contains a button to retrieve movies
- Displays the list of movies in a table format

### movies.php

-Handles the movie retrieval logic
-Connects to the database using the `connectToDatabase()` function
-Retrieves movies from the database using the `GetAllItemsFromDB()` function
-Paginates the results using the offset and limit parameters
-Outputs the results in JSON format

### script.js

-Handles the JavaScript logic for the application
-Retrieves the movies from the server using the fetch API
-Parses the JSON response and displays the list of movies in a table format

### style.css

-The CSS file for the application

## Requirements

To run this application, you will need:

- A PHP interpreter (version 7.2 or higher) to execute the PHP code
- A MySQL database (version 5.6 or higher) to store the movie data
- The mysqli PHP extension to connect to the MySQL database
- A CSV file containing the Netflix titles data (named netflix_titles.csv)

## Setup

1. Create a new directory for the application and add the PHP, HTML, and JavaScript files to it.
2. Create a new MySQL database and import the Netflix titles CSV file into it.
3. Update the `config.php` file with your database connection settings.
4. Configure your web server to serve the application files.
5. Run first insert.php to insert your data in the tables movie and categories
6. Open the `index.html` file in a web browser to access the application.

## Notes

- This project assumes that the Netflix titles CSV file is located in the same directory as the PHP files.
- The database connection settings are defined in `config.php`.
- The application uses a simple pagination system using offset and limit parameters.
- The application outputs the list of movies in a table format.

## Docker Setup

To set up the MySQL database using Docker, run the following command:
` docker run --name test-mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=pass -d mysql`

Then, create a new database and import the Netflix titles CSV file into it:
`create database movies;`
` use movies;`

Create the `categories` and `movie` tables:

```sql
CREATE TABLE categories(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id) );
```

```sql
 CREATE TABLE movie(
  movie_id INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  category_id INT,
  PRIMARY KEY (movie_id),
  FOREIGN KEY (category_id) REFERENCES categories (id) );
```
