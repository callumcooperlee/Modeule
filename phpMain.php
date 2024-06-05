<?php

// Create keyboard when page has loaded
echo "<script>mediaQueryInit();</script>";



// Check if any forms had been submitted before the page refreshed
$_SESSION["isSubmitted"] = isset($_POST['loginType']) ||
														isset($_POST['enterDetails']) ||
														isset($_POST['scale']) ||
														isset($_POST['favourite']);

// If nothing has been submitted, initialise all important session variables
// Also set indicator lights and appropriate forms to their initial state
if ($_SESSION["isSubmitted"] == 0) {
	$_SESSION["username"] = "";
	$_SESSION["password"] = "";
	$_SESSION["startNote"] = "100";
	$_SESSION["scaleType"] = "100";
  $_SESSION["loggedIn"] = "false";
	$_SESSION["loginType"] = "";
	echo "<script>indicatorLightsOff();</script>";
	disableForms("true","false");
}

// Set initial states of favourite-related variables
// Initial state of "favourite" button
$_SESSION["hollow"] = "block";
$_SESSION["fill"] = "none";
// Initially "deleted" is false (a favourite has NOT been deleted)
$_SESSION["deleted"] = "false";



// Check inputs from the user
// If any submit buttons have been pressed:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if ($_SESSION["isSubmitted"] == 1 || isset($_POST['logout'])) {

		// If login type has been changed:
		if (isset($_POST['loginType'])) {
			$_SESSION["loginType"] = $_POST["loginType"];
			// Update in javascript as well
			echo "<script>loginTypeChange()</script>";
		}

		// If login/register button has been submitted:
		if (isset($_POST['enterDetails'])) {

			// Retrieve new username and password
			// Save in session variables
			$_SESSION["username"] = $_POST["username"];
			$_SESSION["password"] = $_POST["password"];

			// Login or register depending on loginType
		  if ($_SESSION["loginType"] == "Login") {
				// Login
				loginCheck();
		  }else{
				// Retrive password validation
				$valid = $_POST["valid"];
				// If password is valid, register new user
				if ($valid=="true") {
					registerCheck();
				}
			}
		}

		// If play button has been pressed:
		if (isset($_POST['scale'])) {
			// Retrive start note and scale type
			getScaleInfo();
			// Play the scale with these values
			echo "<script>playScale(".$_SESSION["startNote"].",".$_SESSION["scaleType"].");</script>";
		}

		// If the favourite button has been pressed
		if (isset($_POST['favourite'])) {
			// Retrive start note and scale type
			getScaleInfo();
			// Use these values to add new row to favourites table
			addFavourite();
		}

		// If logout button has been pressed, set session variable to true
		// Submit button refreshes page, the session variable is checked on load
		if (isset($_POST['logout'])) {
			$SESSION["logout"] = "true";
		}
	}
}



// Update entire page
// Takes place after checking all inputs and session variables
updatePage();

?>
