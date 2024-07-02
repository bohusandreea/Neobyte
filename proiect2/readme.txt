Description
This project is a web-based application that allows users to search for Netflix titles/add limits in a CSV file. The application provides a simple search interface where users can enter a search query, specify a limit and offset, and retrieve a list of matching titles.

Features
Search for Netflix titles by keyword
Specify a limit and offset for pagination
Display search results in a table format
Show execution time and number of records retrieved


Run the Application
Open the terminal and navigate to the project directory.
Run the command php -S localhost:8081 to start the PHP development server.
Open a web browser and navigate to http://localhost:8081.
Enter a search query in the "Search by title" field.
Specify a limit and offset.
Click the "Search" button to retrieve the search results.
The search results will be displayed in a table format below the search form.


Technical Details
The application uses PHP to read and parse a CSV file containing Netflix title data
The search functionality uses a case-insensitive search algorithm to match titles
The application uses HTML and CSS for the user interface
The CSV file is named "netflix_titles.csv" and located in the same directory as the PHP script


Contributing
Contributions are welcome! If you'd like to contribute to this project, please fork the repository and submit a pull request.


