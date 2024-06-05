<html>
<head>
<title>MODEULE</title>
<!-- Link Javascript File -->
<script type="text/javascript" src="javascript.js"></script>
<!-- Link CSS Stylesheet -->
<link rel="stylesheet" href="styles.css">
<!-- Import Icons for forms -->
<script src="https://kit.fontawesome.com/8bcfd9dc90.js" crossorigin="anonymous"></script>
<!-- Import font for screen -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Press+Start+2P|Raleway+Dots">
<?php require("phpInit.php"); ?>
</head>


<body>

<!-- Buttons to choose to login or register -->
<div id="loginButtons">
	<form action="" method="post">
		<!-- Special data for something ?????? -->
		<input type="hidden" name="init" value="true">
		<!-- Press to use form to login -->
		<input type="submit" name="loginType" value="Login" id="chooseLogin" class="loginButtons">
		<!-- Press to use form to register -->
		<input type="submit" name="loginType" value="Register" id="chooseRegister" class="loginButtons">
		<!-- Submit button logs current user out. Only visible when logged in -->
		<input type="submit" name="logout" value="Logout" id="chooseLogout" class="loginButtons">
	</form>
</div>

<!-- Create green lights to indicate if the user is logging in or registering -->
<div id="selectIndicator">
	<div class="selectIndicators"><div class="innerSelectIndicators"></div></div>
	<div class="selectIndicators"><div class="innerSelectIndicators"></div></div>
</div>

<!-- This is the "screen" interface above the keyboard -->
<div id="screen">

	<!-- This div splits the screen in two -->
	<div id="halfScreen">

		<!-- Form to enter username and password -->
		<!-- Only visible when logged out -->
		<!-- Can be used to log in or register depending on which button is pressed -->
		<!-- This is AH Concept of using a form and POST method to log into an account -->
		<div class="loginInput" hidden>
			<form action="" method="post" autocomplete="off">
				<!-- Special data for something ?????? -->
				<input type="hidden" name="valid" value="false">
				<!-- Enter username -->
				<input type="text" name="username" placeholder="Username" minlength="1" maxlength="20" required><br>
				<!-- Enter password, runs function on key release to validate current password -->
			  <input type="password" name="password" placeholder="Password" onkeyup="checkPassword()" onmouseover="showPassword()" onmouseout="hidePassword()" minlength="6" maxlength="20" required>
				<!-- Sumbit Form -->
				<input type="submit" name="enterDetails" value="Login" class="submitButton">
			</form>
		</div>

		<!-- Shows which scale has been filled in to the scale form -->
		<!-- Only visible when not logging in or registering -->
		<div class="audioInfo" id="scaleTypeInfo">
			<div>Current Scale:<br><hr><div class="scaleDescription">-</div><div class="scaleDescription"></div></div>
		</div>

	</div>
	<div id="halfScreen">

		<!-- Text to help user create a valid password. Only visible when registering -->
		<!-- Icons update when typing password to indicate if valid -->
		<div class="loginInput" hidden>
			<div id="passwordAssist">
				<div>Password:</div>
				<div>
					<i class='fas fa-ban letter'></i>
					<i class='fa fa-check letter'></i> Letter
				</div>
				<div>
					<i class='fas fa-ban number'></i>
					<i class='fa fa-check number'></i> Number
				</div>
				<div>
					<i class='fas fa-ban special'></i>
					<i class='fa fa-check special'></i> Special
				</div>
				<div>
					<i class='fas fa-ban passwordSize'></i>
					<i class='fa fa-check passwordSize'></i> 6-20 characters
				</div>
			</div>
		</div>

		<!-- Shows which scale has been filled in to the scale form -->
		<!-- Only visible when not logging in or registering -->
		<div class="audioInfo" id="currentNote">
			<div>Current Note:<br><hr><div class="scaleDescription">-</div></div>
		</div>

	</div>

</div>

<!-- Image underneath keyboard -->
<img src="piano.jpg" id="pianoImage"></img>

<!-- Bar above keyboard -->
<div id="pianoImage">

	<!-- First block of forms - alligned to the left -->
	<div class="formStart">

		<div class="formElements">

			<!-- Form to choose a scale to play or favourite -->
			<form action="" method="post" class="keyboardUI" id="scaleForm">

			<!-- Select the note that the scale will start on -->
			<!-- Each note has a value to offset what note to start on by a certain amount -->
			<!-- If option is changed, runs checkFavourites() to update the favourite icon -->
			<select name="startNote" id="startNote" onChange="checkFavourites()" class="keyboardUI">
				<option value="100" disabled="disabled" selected="selected">Start Note</option>
				<option value="0">C</option>
				<option value="1">C# / Db</option>
				<option value="2">D</option>
				<option value="3">D# / Eb</option>
				<option value="4">E</option>
				<option value="5">F</option>
				<option value="6">F# / Gb</option>
				<option value="7">G</option>
				<option value="8">G# / Ab</option>
				<option value="9">A</option>
				<option value="10">A# / Bb</option>
				<option value="11">B</option>
			</select>

		</div>
		<div class="formElements">

			<!-- Select the scale that will be played -->
			<!-- Each scale has a value corresponding to an item in an array of scales -->
			<!-- If option is changed, runs checkFavourites() to update the favourite icon -->
			<select name="scaleType" id="scaleType" onChange="checkFavourites()" class="keyboardUI">
				<option value="100" disabled="disabled" selected="selected">Scale Type</option>
				<option value="0">Ionian</option>
				<option value="1">Dorian</option>
				<option value="2">Phrygian</option>
				<option value="3">Lydian</option>
				<option value="4">Mixolydian</option>
				<option value="5">Aeolian</option>
				<option value="6">Locrian</option>
				<option value="7">Major Pentatonic</option>
				<option value="8">Minor Pentatonic</option>
				<option value="9">Major Arpeggio</option>
				<option value="10">Minor Arpeggio</option>
			</select>

			</form>

		</div>

		<div class="formElements button favouriteButton">
			<!-- Favourite Button -->
			<!-- Only accessible to a logged in user (AH concept) -->
			<!-- Submits the start note and scale data to be favourited -->
			<!-- Data is stored in a database or deleted if already present (AH concept) -->
			<button type="submit" name="favourite" form="scaleForm" id="favourite" class="iconButton">
			<i class='far fa-heart' id="favouriteIcon"></i>
			<i class='fas fa-heart' id="unfavouriteIcon"></i>
			</button>
		</div>

	</div>

	<div class="formElements button playButton">
		<!-- Play Button -->
		<!-- Submits the start note and scale data to be played -->
		<button type="submit" name="scale" form="scaleForm" class="iconButton"><i class='fas fa-play'></i></button>
	</div>

	<div class="formEnd">

		<div class="formElements">
			<!-- Form to chose a user-specific favourited scale -->
			<!-- Only accessible to a logged in user (AH concept) -->
			<!-- Fills in start note and scale type for the user when an option is selected -->
			<form>
				<select name="favouritesList" onChange="selectFavourite()" class="keyboardUI" id="favouriteForm">
					<!-- Default placeholder -->
					<option name="favDefault" value="100 100" disabled="disabled" selected="selected">Select Favourite</option>
				</select>
			</form>
		</div>

	</div>

</div>

</body>

<?php
require("phpModules.php");
require("phpMain.php");
?>

</html>
