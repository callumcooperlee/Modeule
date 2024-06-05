// Create event listener to check the window width
// If the width is changed, update piano interface
var resizeCheck = window.matchMedia("(max-width: 720px)");
window.addEventListener("resize",mediaQuery);

// Array to store names of all sound files
var allSounds = ["C1","Db1","D1","Eb1","E1","F1","Gb1","G1","Ab1","A1","Bb1","B1","C2","Db2","D2","Eb2","E2","F2","Gb2","G2","Ab2","A2","Bb2","B2"];

// Scale types, all stored in arrays.
// Each item represents a position in an octave.
// The scale will be played up and down.
var ionian = [0,2,4,5,7,9,11,12];
var dorian = [0,2,3,5,7,9,10,12];
var phrygian = [0,1,3,5,7,8,10,12];
var lydian = [0,2,4,6,7,9,11,12];
var mixolydian = [0,2,4,5,7,9,10,12];
var aeolian = [0,2,3,5,7,8,10,12];
var lochrian = [0,1,3,5,6,8,10,12];
var majPent = [0,2,4,7,9,12];
var minPent = [0,3,5,7,10,12];
var majArp = [0,4,7,12];
var minArp = [0,3,7,12];

// Insert all scales into one long array, corresponding to the value of the "scaleType" select input.
var allScales = [ionian,dorian,phrygian,lydian,mixolydian,aeolian,lochrian,majPent,minPent,majArp,minArp];

// Array of note names and scale types. Used to construct the name of a scale for options in the "favourite" select input.
var notes = ["C","C#/Db","D","D#/Eb","E","F","F#/Gb","G","G#/Ab","A","A#/Bb","B"];
var scales = ["Ionian","Dorian","Phrygian","Lydian","Mixolydian","Aeolian","Locrian","Major Pentatonic","Minor Pentatonic","Major Arpeggio","Minor Arpeggio"];

// Initial construction of piano keyboard.
function mediaQueryInit() {
  globalThis.mobileCheck = !resizeCheck.matches;
  mediaQuery(true);
};

// Check the screen width event listener.
// Construct either mobile or PC piano layout depending on screen width.
// This is a media query and therefore an AH Concept
// Pass in init, which contains a boolean of whether the function was triggered by a media query
function mediaQuery(init) {
  if (init != true) {init = false;}
  globalThis.mobileCheck;
  mobile = resizeCheck.matches;
  if (mobileCheck != mobile) {
    mobileCheck = mobile;
    if (mobile == false) {
      createKeysPC(init);
    }else{
      createKeysMobile(init);
    }
  }
}

// Create Div Elements required for a mobile piano
function createKeysMobile(init) {

  // Remove all pre-existing key elements
  destroyKeys();
  var body = document.getElementsByTagName("body")[0];

  // Create array to store two elements to contain a keyboard each
  var keyboardContainer = new Array(2);

  // Create both keyboard containers
  for (i = 0; i < 2; i++) {
    keyboardContainer[i] = document.createElement("div");
    keyboardContainer[i].classList.add("pianoBackground");
    body.appendChild(keyboardContainer[i]);
    keyboardContainer[i].style.setProperty("--fromBottom","0");
  }

  // Bring top keyboard above the bottom one and set height of bottom keyboard to 25%
  keyboardContainer[1].style.zIndex="3";
  keyboardContainer[0].style.setProperty("--height","25%");

  // Function to set final styles of top keyboard (25% height & displaced upwards by 23%)
  function mobileStyles() {
      keyboardContainer[1].style.setProperty("--height","25%");
      keyboardContainer[1].style.setProperty("--fromBottom","23%");
  }

  // Check if animation is required. Only required if triggered by media query.
  // Build keyboard in both cases.
  // Pass in 1 to indicate that only 1 octave needs to be built in each div
  if (init == false) {

    // Triggered by media query so animate.
    keyboardContainer[1].style.setProperty("--height","50%");
    buildKeyboard(1);
    setTimeout(mobileStyles,1);
  }else{

    // Do not animate. Triggered by a refresh or a submit.
    mobileStyles(keyboardContainer);
    buildKeyboard(1);
  }
}

// Create Div Elements required for a PC piano
function createKeysPC(init) {

  // Remove all pre-existing key elements
  destroyKeys();
  var body = document.getElementsByTagName("BODY")[0];

  // Create variable store an elements to contain the keyboard
  keyboardContainer = document.createElement("div");
  keyboardContainer.classList.add("pianoBackground");
  body.appendChild(keyboardContainer);
  keyboardContainer.style.setProperty("--height","25%");
  keyboardContainer.style.setProperty("--fromBottom","23%");

  // Function to set final styles of the keyboard (50% height & at bottom of screen)
  function PCStyles() {
    keyboardContainer.style.setProperty("--height","50%");
    keyboardContainer.style.setProperty("--fromBottom","0");
  }

  // Check if animation is required. Only required if triggered by media query.
  // Build keyboard in both cases.
  // Pass in 2 to indicate that 2 octaves need to be built
  if (init == false) {
    buildKeyboard(2);
    setTimeout(PCStyles,1);
  }else{
    PCStyles();
    buildKeyboard(2);
  }
}

// Adds classes to appropriate Divs to style them like keys
// Adds an indent to Divs to space keys appropriately
function buildKeyboard(octaves) {

  // Calculate the number of keys and key widths based on the number of octaves
  var noKeys = octaves*7
  var keyWidthWhite = 100/7/octaves;
  var keyWidthBlack = keyWidthWhite/2;

  // Get all keyboard containers
  var keyboard = document.querySelectorAll(".pianoBackground");

  // Loop for the number of keyboards
  for (j = 0; j < keyboard.length; j++) {

    // Initialise the indent of white and black keys
    var indentWhite = 0;
    var indentBlack = keyWidthBlack*1.5;

    // Create array to store all piano key divs
    var keys = new Array(noKeys);

    // Loop for every key
    for (i = 0; i < noKeys; i++) {

      // Create a white key
      createKeys(keys[i],keyboard[j],"whiteKey",keyWidthWhite,indentWhite);

      // Create a black key depending on the counter
      if (i/2 == 1 || i/6 == 1 || i/9 == 1 || i/13 == 1) {}else{
        createKeys(keys[i],keyboard[j],"blackKey",keyWidthBlack,indentBlack);
      }

      // Increase the indent of the next key
      indentWhite += keyWidthWhite;
      indentBlack += 2*keyWidthBlack;
    }
  }
}

// Create a key
// The "keyClass" is either "white" or "black", determining how it is styled
function createKeys(key,keyboard,keyClass,keyWidth,keyIndent) {
  key = document.createElement("div");
  key.classList.add(keyClass);
  key.setAttribute("onclick","keyPress(this)");
  keyboard.appendChild(key);
  key.style.setProperty('--keyWidth',String(keyWidth).concat("%"));
  key.style.setProperty('--indentLeft',String(keyIndent).concat("%"));
}

// Function used to delete all pre-existing keys
function destroyKeys() {
  var keyboardContainer = document.querySelectorAll(".pianoBackground");
  for (i = 0; i < keyboardContainer.length; i++) {
    keyboardContainer[i].remove();
  }
}

// Function to play a key
// Runs when a key is pressed, also used to play each note of a scale
function keyPress(key) {

  // Get all keys into array
  var allKeys = document.querySelectorAll(".whiteKey,.blackKey");

  // Start animation on the key and create audio element
  key.classList.add("pressed");
  audioPlay = document.createElement("audio");

  // Set timer to set the key to normal and delete the audio element after 0.5s
  setTimeout(function time(){
    key.classList.remove("pressed");
    audioPlay.remove();
  }, 500);

  // Loop for every key
  for (i = 0; i < allKeys.length; i++) {

    // If key matches the key that was clicked
    if (key == allKeys[i]) {

      // Add the correct source to the audio tag and play
      var source = "Audio//".concat(globalThis.allSounds[i].concat(".mp3"))
      audioPlay.src = source;
      audioPlay.play();

      // Update the screen to display which note is being played
      var j = i;
      if (j > 11) {
        j -= 12;
      }
      showInfo();
      document.querySelectorAll(".scaleDescription")[2].innerHTML=notes[j];
      break;

    }
  }
}

// Function to play a selected scale. Correct start note and scale type is passsed in.
// Uses the keyPress() function to play each key
function playScale(startNote,scaleType) {

  // Get correct scale and format to include the rest of the scale in reverse.
  var scale = allScales[scaleType];
  var scaleReverse =  scale.map((x) => x);scaleReverse.pop();scaleReverse.reverse();
  var finalScale = scale.concat(scaleReverse);

  // Get all keys into an array
  var allKeys = document.querySelectorAll(".whiteKey,.blackKey");
  var j = 0;

  // Loop every 0.5s to play each note of the scale until complete
  var metronome = setInterval(function time() {

    // Offset scale by startNote
    var keyPos = finalScale[j] + startNote;
    var key = allKeys[keyPos];
    keyPress(key);
    j += 1;
    if (j == finalScale.length){
      clearInterval(metronome);
    }

  }, 500);

}

// Update value of login submit to inform the user if they are loggin in or registering
// Save the login type to local storage to use elsewhere
function updateLoginType(loginType) {
	document.querySelector(".submitButton").value=loginType;
  localStorage.setItem("loginType",loginType);
  var indicatorLights = document.querySelectorAll(".innerSelectIndicators");
  if (loginType == "Login") {
    indicatorLights[0].classList.add("lightUp");
    indicatorLights[1].classList.remove("lightUp");
  }else{
    indicatorLights[1].classList.add("lightUp");
    indicatorLights[0].classList.remove("lightUp");
  }
  // Update password validation
  checkPassword();
}

// If the type of login has changed, save to localStorage for use after the page refreshes
function loginTypeChange() {
  localStorage.setItem("loginTypeChange","true");
}

// Update the screen to show either the scale information or the login form
// Also update the indicator lights next to the login type buttons
function showInfo() {
  var state = localStorage.getItem("loginTypeChange");
  for (i = 0; i < 2; i++) {
    if (state == "true") {
      document.querySelectorAll(".audioInfo")[i].style.display="none";
      document.querySelectorAll(".loginInput")[i].hidden=false;
      if (localStorage.getItem("loginType") == "Login") {
        document.querySelectorAll("#halfScreen")[1].style.visibility="hidden";
      }else{
        document.querySelectorAll("#halfScreen")[1].style.visibility="visible";
      }
    }else{
      indicatorLightsOff();
      document.querySelectorAll(".audioInfo")[i].style.display="flex";
      document.querySelectorAll(".loginInput")[i].hidden=true;
      document.querySelectorAll("#halfScreen")[1].style.visibility="visible";
    }
  }
  localStorage.setItem("loginTypeChange",false);
}

// Function used to switch off the indicator lights next to the login type buttons
function indicatorLightsOff() {
  var indicatorLights = document.querySelectorAll(".innerSelectIndicators");
  indicatorLights[0].classList.remove("lightUp");
  indicatorLights[1].classList.remove("lightUp");
}


// Save the scale form so that it does not reset if the page is refreshed
function keepScale(startNote,scaleType) {

  // Update the screen above the keyboard
  showInfo();

  // If scale is favourited, indicate in favourite select element
  var scaleInfo = String(startNote).concat(" ").concat(String(scaleType));
  var favouriteOption = document.querySelectorAll(".favouriteOption");
  for (i = 0; i < favouriteOption.length; i++) {
    if (favouriteOption[i].value == scaleInfo) {
      document.getElementsByName("favouritesList")[0].value=scaleInfo;
    }
  }

  // Get the start note and scale type
  document.getElementById("startNote").value=startNote;
  document.getElementById("scaleType").value=scaleType;
  if (startNote != "100" && scaleType != "100") {
    var screenScaleNote = document.querySelectorAll(".scaleDescription")[0];
    var screenScaleType = document.querySelectorAll(".scaleDescription")[1];

    // display scale type on screen
    screenScaleNote.innerHTML=notes[startNote];
    screenScaleType.innerHTML=scales[scaleType];
  }
  document.querySelectorAll(".scaleDescription")[2].innerHTML="-";
}

// Save the username & password so that they does not reset if the page is refreshed
function keepForms(username,password) {
  if (username != "") {
    document.getElementsByName("username")[0].value=username;
  }
  if (password != "") {
    document.getElementsByName("password")[0].value=password;
  }
}

// Disable elements depending on if the user is logged in or not
function disableInput(id,state) {
  if (state == "true") {
    document.getElementById(id).disabled=true;
  }else{
    document.getElementById(id).disabled=false;
  }
}

// Update password assistance on the screen when registering
function updatePasswordAssist(password,type,assistClass) {
  if (type.test(password)) {
    document.querySelectorAll(assistClass)[0].style.display="none";
    document.querySelectorAll(assistClass)[1].style.display="inline";
  }else{
    document.querySelectorAll(assistClass)[0].style.display="inline";
    document.querySelectorAll(assistClass)[1].style.display="none";
  }
}

// Validate Password - ran every time a user enters a character into the password input
function checkPassword() {

  // Only validate if the user is loggin in
  if (localStorage.getItem("loginType") == "Register") {

    // Check for special character, number and letter
    var password = document.getElementsByName("password")[0].value;
    var special = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
    var letter = /[a-zA-Z]/;
    var number = /\d/;

    // Update value of hidden form element. This is used in php to check if the password is valid or not
    if (special.test(password) && letter.test(password) && number.test(password)) {
      document.getElementsByName("valid")[0].value = "true";
    }else{
      document.getElementsByName("valid")[0].value = "false";
    }

    // Update screen depending on how valid the password is
    updatePasswordAssist(password,letter,".letter");
    updatePasswordAssist(password,number,".number");
    updatePasswordAssist(password,special,".special");
    if (password.length >= 6 && password.length <= 20) {
      document.querySelectorAll(".passwordSize")[0].style.display="none";
      document.querySelectorAll(".passwordSize")[1].style.display="inline";
    }else{
      document.querySelectorAll(".passwordSize")[0].style.display="inline";
      document.querySelectorAll(".passwordSize")[1].style.display="none";
    }
  }
}

// When user hovers cursor over password input box, make the password visible
function showPassword() {
  document.getElementsByName("password")[0].type="text";
}

// When user's cursor is not over the password input box
function hidePassword() {
  document.getElementsByName("password")[0].type="password";
}

// Delete all options in favourite select
// Create new options taken directly from favourites table (AH Concept)
function updateFavourites(startNote,scaleType) {
  var scaleString = notes[startNote].concat(" ").concat(scales[scaleType]);
  var scaleInfo = String(startNote).concat(" ").concat(String(scaleType));
  var favouriteOption = document.querySelectorAll(".favouriteOption");
  favouriteOption = document.createElement("option");
  favouriteOption.classList.add("favouriteOption");
  favouriteOption.innerHTML = scaleString;
  favouriteOption.value = scaleInfo;
  favouriteOption.selected = "selected";
  document.getElementsByName("favouritesList")[0].appendChild(favouriteOption);
}

// If user selects different favourite option, fill in scales form appropriately
function selectFavourite() {
  var values = document.getElementsByName("favouritesList")[0].value;
  const noteAndScale = values.split(" ");
  var startNote = noteAndScale[0];
  var scaleType = noteAndScale[1];
  keepScale(startNote,scaleType);
  if (values != "100 100") {
    updateFavouriteIcon("none","block");
  }else{
    updateFavouriteIcon("block","none");
  }
}

// When user selects a different option from the scales form,  screen and favourite select input
function checkFavourites(startNote,scaleType) {
  showInfo();
  var favouriteOption = document.querySelectorAll(".favouriteOption");
  if (!startNote && !scaleType && startNote != 0 && scaleType != 0) {
    var startNote = document.getElementsByName("startNote")[0].value;
    var scaleType = document.getElementsByName("scaleType")[0].value;
  }
  if (startNote != "100" && scaleType != "100") {
    // display scale type on screen
    var screenScaleNote = document.querySelectorAll(".scaleDescription")[0];
    var screenScaleType = document.querySelectorAll(".scaleDescription")[1];
    screenScaleNote.innerHTML=notes[startNote];
    screenScaleType.innerHTML=scales[scaleType];
  }
  var scaleInfo = String(startNote).concat(" ").concat(String(scaleType));
  document.getElementsByName("favDefault")[0].selected = "selected";
  updateFavouriteIcon("block","none");
  for (i = 0; i < favouriteOption.length; i++) {
    if (favouriteOption[i].value == scaleInfo) {
      favouriteOption[i].selected = "selected";
      updateFavouriteIcon("none","block");
    }
  }
}

// Update favourite icon to hollow or filled
function updateFavouriteIcon(hollow,fill,deleted) {
  hollow = "display:".concat(hollow);
  fill = "display:".concat(fill);
  document.getElementById("favouriteIcon").style=hollow;
  document.getElementById("unfavouriteIcon").style=fill;
  if (deleted == "true") {
    document.getElementsByName("favDefault")[0].selected = "selected";
  }
}
