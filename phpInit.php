<?php

// If there is an active session
if (isset($_SESSION)) {
  // If logout button has been pressed
  if ($_SESSION["logout"] == "true") {
    // Destroy the session
    session_destroy();
  }
}

// Start a new session
session_start();

// Function to connect to a database
function dbConnect($host,$db_username,$db_password,$database) {
  // Values for host , username , password and database are passed in
  // Attempt to connect to database
  $conn = mysqli_connect("$host","$db_username","$db_password","$database");
  // If unsuccessful, close connection.
  if (!$conn) {
    mysqli_close($conn);
  }
  // Set connection as session variable
  $_SESSION["conn"] = $conn;
}

// Connect to database with correct data
dbConnect("localhost","username","password","Modeule");
?>
