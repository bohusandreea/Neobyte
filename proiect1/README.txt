This project is a simple contact form that sends an email using Mailtrap. 
The form validates user input and displays error messages if the input is invalid. 
The form also includes a loading bar that appears when the form is submitted.

Files
index.html: The main HTML file that contains the contact form.
send_email.php: The PHP script that sends the email using Mailtrap.
style.css: The CSS file that styles the contact form and success page.
success.html: The HTML file that displays a success message after the email is sent.


How to Use
Open the terminal and navigate to the project directory.
Run the command php -S localhost:8081 to start the PHP development server.
Open a web browser and navigate to http://localhost:8081.
Fill out the contact form and click the "Send Email" button.
If the form is valid, the email will be sent using Mailtrap.
If the email is sent successfully, the user will be redirected to success.html.


Dependencies
Mailtrap API key (replace with your own API key in send_email.php) 
PHPMailer library (included in vendor/autoload.php)

