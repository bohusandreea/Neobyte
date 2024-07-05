This repository contains a PHP project that connects to a MySQL database, imports data from a CSV file,
and provides endpoints for retrieving data in JSON format.

Files
index.php
Connects to the database and imports data from the CSV file
Provides a simple web interface to display all data from the database
Supports pagination with offset and limit parameters

allitemsDB.php
Retrieves all data from the database and displays it in a table format

csvToJson.php
Retrieves data from the database and outputs it in JSON format
Supports pagination with offset and limit parameters

Pagination
The API supports pagination with offset and limit parameters. For example, to retrieve 20 records starting from the 10th record, use the following URL: localhost:8080/index.php?offset=10&limit=20

$ docker run --name test-mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=pass -d mysql

create database movies;

use movies;

/* create table categories*/
CREATE TABLE categories(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

/*create table movie*/
CREATE TABLE movies (
  movie_id INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  category_id INT,
  PRIMARY KEY (movie_id),
  FOREIGN KEY (category_id) REFERENCES categories (category_id)
);
