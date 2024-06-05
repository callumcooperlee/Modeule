<?php

// Function to disable forms depending on if user is logged in
function disableForms($state1,$state2) {
	echo "<script>disableInput('favourite','$state1');</script>";
	echo "<script>disableInput('chooseLogout','$state1');</script>";
	echo "<script>disableInput('favouriteForm','$state1');</script>";
	echo "<script>disableInput('chooseLogin','$state2');</script>";
	echo "<script>disableInput('chooseRegister','$state2');</script>";
}

// Function to efficiently retrieve start note and scaletype
function getScaleInfo() {
	$_SESSION["startNote"] = $_POST["startNote"];
	$_SESSION["scaleType"] = $_POST["scaleType"];
}

// Function to attempt to log the user in
function loginCheck() {

	// Get database connection
	$conn = $_SESSION["conn"];

	// Get the username and password
	$username = $_SESSION["username"];
	$password = $_SESSION["password"];

	// Run query to check if the password matches an existing username
	$query = 'SELECT password FROM users WHERE username LIKE "%'.$username.'%"';
	$rows = mysqli_query($conn, $query) or die(mysqli_error($conn));

	// If there is nothing returned the username is invalid
	if (mysqli_num_rows($rows)==0) {
	  $_SESSION["loggedIn"] = "false";
	}else{
	  // If the password doesn't match, user will not be logged in
	  if (mysqli_fetch_array($rows)['password'] != $password) {
	    $_SESSION["loggedIn"] = "false";
	  }else{
	    // Login successful
	    $_SESSION["loggedIn"] = "true";
	  }
	}

}

// Function to attempt to register a new user
function registerCheck() {

	// Get database connection
	$conn = $_SESSION["conn"];

	// Get the username and password
	$username = $_SESSION["username"];
	$password = $_SESSION["password"];

  // Run query to add new user to the "users" table
  $query = "INSERT INTO users (username,password) VALUES ('$username','$password')";
  $queryData = mysqli_query($conn,$query) or die (mysqli_error($conn));

	// Log the new user in
	loginCheck();

}

// Add (or remove) a favourite scale
function addFavourite() {

	// Get database connection
	$conn = $_SESSION["conn"];

	// Get current username, the start note and the scale type
	$username = $_SESSION["username"];
	$startNote = $_SESSION["startNote"];
	$scaleType = $_SESSION["scaleType"];

	// If both start note and scale type have been selected
	if ($startNote != 100 && $scaleType != 100) {

	  // Run query to get all fvaourite scales from the favourites database (AH Concept)
	  $query = 'SELECT startNote,scaleType FROM favourites WHERE username LIKE "%'.$username.'%"';
	  $queryData = mysqli_query($conn,$query) or die (mysqli_error($conn));

	  // Assume the scale is not favouirited
	  $favourited = "false";

	  // Check all rows for a scale matching the session data.
	  if (mysqli_num_rows($queryData) > 0) {
	    while ($row = mysqli_fetch_array($queryData)) {
	      if ($row["startNote"] == $startNote && $row["scaleType"] == $scaleType) {
	        // A match has been found so update favourited boolean
	        $favourited = "true";
	      }
	    }
	  }

	  // If a matching favourite was found, delete it. This means the user has unfavourited a scale
	  if ($favourited == "true") {
	    $query = "DELETE FROM favourites WHERE startNote=".$startNote." AND scaleType=".$scaleType."";
	    $queryData = mysqli_query($conn,$query) or die(mysqli_error($conn));
	    // Update session so the favourite input reverts to default option
	    $_SESSION["deleted"] = "true";
	  // If there is no favourite that matches, add it to the table
	  }else{
	    $query = "INSERT INTO favourites (username,startNote,scaleType) VALUES ('$username','$startNote','$scaleType')";
	    $queryData = mysqli_query($conn,$query) or die(mysqli_error($conn));
	    // Set filled state for the favourite button
	    $_SESSION["hollow"] = "none";
	  	$_SESSION["fill"] = "block";
	  }
	}

}

// Retrive the current user's favourites and update the list
function updateFavouriteList() {

	// Get database connection
	$conn = $_SESSION["conn"];

	// Get the current username
	$username = $_SESSION["username"];

	// Run query to get all records from the "favourites" table with matching username
	$query = 'SELECT * FROM favourites WHERE username LIKE "%'.$username.'%"';
	$queryData = mysqli_query($conn,$query) or die (mysqli_error($conn));

	// If a result was found
	if (mysqli_num_rows($queryData) > 0) {
	  // Loop through all records
	  while ($row = mysqli_fetch_array($queryData)) {
	    // Get the start note and scale type of each favourite
	    $startNote = $row["startNote"];
	    $scaleType = $row["scaleType"];
	    // Create the option in the favourite select input
	    echo "<script>updateFavourites(".$startNote.",".$scaleType.");</script>";
	  }
	}

}


// Update whole page
function updatePage() {

	// Get database connection
	$conn = $_SESSION["conn"];

  // If login type is set:
  if ($_SESSION["loginType"] == "Login" || $_SESSION["loginType"] == "Register") {
  	// If login type is Login:
  	if ($_SESSION["loginType"] == "Login") {
  		// Update in javascript
  		echo "<script>updateLoginType('Login');</script>";
  	// If login type is Register
  	}else{
  		// Update in javascript
  		echo "<script>updateLoginType('Register');</script>";
  		// Update password assistance
  		echo "<script>checkPassword();</script>";
  	}
  }

	// If the user is logged in:
	if ($_SESSION["loggedIn"] == "true") {
		// Update the user's favourites list
		updateFavouriteList();
		// Disable appropriate forms (login/register)
		disableForms("false","true");
		// Update favourite button
		echo "<script>updateFavouriteIcon('".$_SESSION["hollow"]."','".$_SESSION["fill"]."','".$_SESSION["deleted"]."');</script>";
		// Make sure scale form is consistent with selected favourite option
		echo "<script>checkFavourites(".$_SESSION["startNote"].",".$_SESSION["scaleType"].");</script>";
		// Switch off indicator lights
		echo "<script>indicatorLightsOff()</script>";
	// If user is not logged in
	}else{
		// Disable appropriate forms (favourite,logout)
		disableForms("true","false");
	}

	// If a login / register attempt is invalid, forms will be filled in with the attempt data
	echo "<script>keepForms('".$_SESSION["username"]."','".$_SESSION["password"]."');</script>";
	// If page has been refreshed, restore scale form to keep values from before the refresh
	echo "<script>keepScale(".$_SESSION["startNote"].",".$_SESSION["scaleType"].");</script>";

}



?>
