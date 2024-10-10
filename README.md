# Modeule
An online musical tool, a personal project for SQA Advanced Higher 2021.<br>
Full documentation has been provided and a demo video which can also be found at https://www.youtube.com/watch?v=1-Fqnq-HtUw.

## Setup
<ul>
  <li>Begin by installing XAMPP: https://www.apachefriends.org/download.html</li>
  <li>Download files or fork and clone into C:\xampp\htdocs\Modeule</li>
  <li>Start the XAMPP Control Panel and click start for Apache and MySQL</li>
  <li>Navigate to http://localhost/phpmyadmin</li>
  <li>Create a database named 'Modeule'</li>
  <li>Create a user with username 'username' and password 'password'</li>
  <li>Use the SQL below to create apropriate tables</li>
</ul>

## SQL for table creation
<ul>
  <li>CREATE TABLE users (<br>
username varchar(20) NOT NULL PRIMARY KEY,<br>
password varchar(20) NOT NULL<br>
);</li><br>
  <li>CREATE TABLE favourites (<br>
username varchar(20) NOT NULL,<br>
startNote varchar(10) NOT NULL,<br>
scaleType varchar(10) NOT NULL,<br>
FOREIGN KEY (username) REFERENCES users(username)<br>
);</li>
</ul>

## To run,
<ul>
  <li>Navigate to http://localhost/Modeule/mainPage.php</li>
</ul>
