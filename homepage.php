<?php
session_start();
if(!(isset($_SESSION["loggedin"] ))){
header("location: login.php");
}

require_once "config.php";
$user = $_SESSION["username"];

if(isset($_POST["folderName"])) {
	if(isset($_POST["delete"])) {
		//If Delete Is Submitted
		$delFolderName = $_POST["folderName"];
			if(isset($_POST["fileName"])) {
				//Delete Just A File in the Folder
				$delFileName = $_POST["fileName"];
				$deleteFile = "DELETE FROM files WHERE user ='$user' AND name= '$delFileName' AND fname ='$delFolderName'";
				mysqli_query($link, $deleteFile);
			} else {
				//Delete The Whole Folder
			$deleteFolder = "DELETE FROM folders WHERE user ='$user' AND fname ='$delFolderName'";
			$deleteFilesInFolder = "DELETE FROM files WHERE user='$user' AND fname='$delFolderName'";
			mysqli_query($link, $deleteFolder);
			mysqli_query($link, $deleteFilesInFolder);
		}
	} else {
		$folderName = $_POST["folderName"];
		//Create File If FileName is Present, and File does not already exist
		if(isset($_POST["fileName"])){
			$fileName = $_POST["fileName"];
			$fileDesc = $_POST["fileDesc"];
			$fileSnippit = $_POST["fileSnippit"];
			$fileColor = $_POST["fileColor"];
			$insertFile = "INSERT INTO files values('$fileName', '$user', '$fileDesc', '$fileSnippit', '$folderName', '$fileColor', 0, 0)";
			$dontInsertFile = "SELECT name FROM files WHERE name='$fileName' AND user='$user' AND fname='$folderName'";
			$checkInsertFile = mysqli_query($link, $dontInsertFile);
			if(mysqli_num_rows($checkInsertFile) == 0) {
				mysqli_query($link, $insertFile);
			}
			} else {
			//Create new folder if folder does not already exist
			$insertFolder = "SELECT fname FROM folders WHERE user ='$user' AND fname='$folderName'";
			$dontInsertFolder = mysqli_query($link, $insertFolder);
			if(mysqli_num_rows($dontInsertFolder) == 0) {
				$folderI = "INSERT into folders values ('$user', '$folderName');";
				if(mysqli_query($link, $folderI)){
				} else {
				 echo "Error, inserting Folder failed" .$folderI ;
				}
			}
			}
 }
}

$sql = "SELECT fname FROM folders WHERE user ='$user'";
    $result = mysqli_query($link, $sql);
?>



<!DOCTYPE html>
<html>
<head>
<script src="jquery-3.4.1.min.js"></script>
<title>Homepage</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
  box-sizing: border-box;
}
.row {
  display: flex;
}
.right {
  flex: 65%;
  padding: 15px;
}
.left {
  flex: 15%;
  padding: 15px 0;
}
.topnav {
  overflow: hidden;
  background-color: #4e0066;
}
.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}
.topnav a:hover {
  background-color: #6EA4BF;
  color: black;
}
.topnav a.active {
  background-color: #41337A;
  color: white;
}
.topnav b {
  float: right;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

/* Navbar container */
.navbar {
  overflow: hidden;
  background-color: #333;
}

/* Links inside the navbar */
.navbar a {
  float: left;
  font-size: 17px;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

/* The dropdown container */
.dropdown {
  float: left;
  overflow: hidden;
}

/* Dropdown button */
.dropdown .dropbtn {
  font-size: 16px;
  border: none;
  outline: none;
  color: white;
  padding: 14px 16px;
  background-color: inherit;
  font-family: inherit; /* Important for vertical align on mobile phones */
  margin: 0; /* Important for vertical align on mobile phones */
}

/* Add a red background color to navbar links on hover */
.navbar a:hover, .dropdown:hover .dropbtn {
  background-color: #f2f2f2;
	color: black;
}

/* Dropdown content (hidden by default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  float: none;
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  text-align: left;
}

/* Add a grey background color to dropdown links on hover */
.dropdown-content a:hover {
  background-color: #ddd;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
  display: block;
}

/* Style the tab */
.tab {
  float: left;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
  width: 100%;
  height: 300px;
}

/* Style the buttons inside the tab */
.tab button {
  display: block;
  background-color: inherit;
  color: black;
  width: 100%;
  padding: 18px 12px;
  border: none;
  outline: none;
  text-align: left;
  cursor: pointer;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current "tab button" class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  float: left;
  border 2px solid #ECFEE8
  width: 100%;
  border-left:none;
  border-right:none;
  height: 300px;
}
/*Defining the style of the accordion*/
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 90%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #41337A;
  color: #ECFEE8;
}
/*Define the styling of a panel */
.panel {
  padding: 0 18px;
  display: none;
  background-color: #ECFEE8;
  overflow: hidden;
}
/* Second panel that has diferent colors and padding */
.panel2 {
  padding: 0 12px;
  display: none;
  background-color: #4E0066;
  width: 90%;
  overflow: hidden;
}


body {
  font-family: Arial, Helvetica, sans-serif;
}
.dark-mode {
  background: #5D5C55 !important;
  color: white;
}

/* Style the header */
.header {
  background-color: #C2EFEB;
  padding: 8px;
  text-align: center;
  font-size: 14px;
  color: gray;
}

/*Style the folder/file buttons*/
.fButton {
	background-color: #f2f2f2;
	text-align: center;
	font-size: 12px;
	color: gray;
}

/* Style the fileMenu */
.fileMenu{
 background-color: #4E0066;
  padding: 18px;
  text-align: Left;
  font-size: 18px;
  color: white;
}

/* Folder Creation Modal Block */
.folderForm {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 10px; /* Location of the box */
  left: 400;
  top: 0;
  width: 20%; /* Full width */
  height: 40%; /* Full height */
  overflow: auto; /* Enable scroll if needed */

}

/* Folder Creation Content */
.folderText {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 100%;
}

/* Folder Creation Close Button */
.folderModalClose {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.folderModalClose:hover,
.folderModalClose:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

/* File Creation Modal Block */
.fileForm {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 10px; /* Location of the box */
  left: 400;
  top: 0;
  width: 20%; /* Full width */
  height: 50%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: #fefefe
}

/* File Deletion Modal Block */
.deleteFileForm {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 10px; /* Location of the box */
  left: 1100;
  top: 150;
  width: 20%; /* Full width */
  height: 22%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: #fefefe
}

/* Folder Deletion Modal Block */
.deleteFolderForm {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 10px; /* Location of the box */
  left: 1100;
  top: 150;
  width: 20%; /* Full width */
  height: 15%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: #fefefe
}

.fileCreateText {
	white-space: pre;
}

/* Pre is used to get rid of whitespace in code snippets */
pre{
white-space: pre;
}

/* Style the footer */
footer {
  background-color: #777;
  padding: 10px;
  text-align: center;
  color: white;
}

</style>
</head>
<!-- Set up the actual body of the page -->
<body style= "background:#FFFCE8">

<!-- Begin top navigation bar -->
<div class="topnav">
<!-- Left floating buttons -->
  <a class="active" href="#home">Home</a>
  <a href="#contact">Contact</a>
  <a href="#about">About</a>
<!-- Right floating buttons -->
  <!-- <b href="#settings" >Settings</b> -->
	<div class="dropdown">
	    <button class="dropbtn">Settings
	      <i class="fa fa-caret-down"></i>
	    </button>
	    <div class="dropdown-content">
	      <button onclick = "darkmode()">Change Theme</button>
	      <a href="logout.php">Logout</a>
	      <!-- <a href="#">Link 3</a> -->
	    </div>
	  </div>
  <!-- <a href="logout.php" >Logout</a> -->
</div>

<!-- End top navigation bar-->

<!-- Begin the row and flex elements -->
<div class="row">

<div class="left"><!--This is the left flex, which contains the folders and searchbar -->

<div class="fileMenu">Files
<input type="text" id="search" onkeyup = "searchbar()" placeholder="Search..">
<button class="fButton" onclick="document.getElementById('folderModal').style.display='block'" id="folderModalButton">New Folder...</button>
</div>

<?php
     $arrayParm=array();
		 $arrayChed=array(); // cheese array for snippit description
     $arrayFetta=array();
		 $arrayJack=array();
		 $arrayMuns=array();
		 $arrayEndMe=array();
 if (mysqli_num_rows($result) > 0) { //If there are rows in the original Mysql query, then set up arrays and find the files
      $arraye = array();
			$arrayd = array(); // array for snippit description
      $arraya = array();
      $folderJson = array(array());
     // $arrayParm = array();
     // $arrayFetta = array();
      $b = 0;
			$c = 0;
      /*While there are Folders in the row, get the information from them. */
      while($row = mysqli_fetch_assoc($result)) {
        $param_Tname = $row["fname"];
        $sql1 = "SELECT name, description, snippit, recent from files where fname = '$param_Tname'";
        $result2 = mysqli_query($link, $sql1);
        if($result2 != false){
        $a = 0;
?>
<!-- Create the folder acordion -->
<button class="accordion" onclick="setFolder(this.innerHTML);"><?php echo $param_Tname ?> </button>
<div class ="panel2" data-value=<?php echo $param_Tname; ?>>
<div class="tab">
<?php
        if (mysqli_num_rows($result2) > 0) {
        while($row = mysqli_fetch_assoc($result2)) {
	/* These are the arrays that we use to create buttons with */
	$arraye[$a] = $row["name"];
	$arrayd[$a] = $row["description"];
	$arraya[$a] = $row["snippit"];
	$arrayc[$a] = $row["recent"];
	/* These are cheese arrays I am using to avoid problems */
	$arrayParm[$b] = $row["name"];
	$arrayChed[$b] = $row["description"];
	$arrayFetta[$b] = $row["snippit"];
//	if( //tHe array where I am trying to store data does not exits

	/* Work in progress to try and use JSON */
	if(! (isset($incomingFolder))){
	$incomingFolder = array($param_Tname => array('filename' => $row["name"], 'snippit' => $row["snippit"] ));
	}
	else{
	$tempArray = array($param_Tname => array('filename' => $row["name"], 'snippit' => $row["snippit"] ));
	$incomingFolder[$param_Tname][$a] = $tempArray;
	/* Work in progess to use JSON*/
	}
	/* Create the buttons to be called by openFile */
	$taxes = "'";
	$str = htmlentities($row["name"]);
	if($row["recent"] > 0){
        echo'<button class="tablinks" onclick="openFile(event, '.$taxes.$str.$b.$taxes.'); setFile(this.innerHTML); setName('.$str.')";id="defaultOpen">'.$row["name"].' </button>';
				$arrayJack[$c] = $str.$b; /* Here the variable $b is to avoid non-unique names */
				$c++;
 	}else{

	 echo'<button class="tablinks" onclick="openFile(event, '.$taxes.$str.$b.$taxes.'); setFile(this.innerHTML); setName('.$str.'); ">'.$row["name"].' </button>';
	}

	$a++;
	$b++;
        }
	//More JSOn
	$folderJson = array_merge($folderJson, $incomingFolder);
      }

	}
?>
<!-- Examples of how to create buttons for reference -->
 <!-- <button class="tablinks" onclick="openCity(event, 'Forloop')" id="defaultOpen">Forloop</button>
  <button class="tablinks" onclick="openCity(event, 'Quicksort')">Quicksort</button>
  <button class="tablinks" onclick="openCity(event, 'Array')">Array</button> -->
</div>
</div>
<?php
}
}

// json_encode($folderJson);
?>
</div>


<!--
Example of how to create an accordion for reference
<button class="accordion">File 2</button>
<div class="panel">
</div>-->

<!-- End left flex and move to right side of the screen -->
<div class="right">
<div class = "header">
<h2>FreedomFlow (Database graciously provided by MTU IT)</h2>
</div>

<!-- These Buttons Open the Confirm Deletion Box, Top One for Deleting Files and Bottom for Deleting Whole Folders -->
<button class="fButton" onclick="document.getElementById('deleteFileModal').style.display='block'; document.getElementById('deleteFolderModal').style.display='none'" id="fileDeleteButton">Delete File</button>
<button class="fButton" onclick="document.getElementById('deleteFolderModal').style.display='block'; document.getElementById('deleteFileModal').style.display='none'" id="folderDeleteButton">Delete Folder</button>
<button class="fButton" onclick="document.getElementById('fileModal').style.display='block'" id="fileCreateButton">Create File</button>
<br>

<?php
//For each folder in the array
for($i = 0; $i < count($arrayParm); $i++){

?>
<!-- make the content -->
<div id = <?php echo $arrayParm[$i].$i; ?> class="tabcontent">
<div style="white-space: pre-wrap;">
	&#10;
</div>
<h3><?php echo $arrayParm[$i]; ?></h3>
<p>
<?php echo $arrayChed[$i]; ?>
</p>
<pre>
   <?php echo $arrayFetta[$i];?>
</pre>
</div>
<?php }
?>

<div style="white-space: pre-wrap;">
	&#10;
</div>

</div>
	<!-- Folder Creation Content -->
<div id="folderModal" class="folderForm">
    <span onclick="document.getElementById('folderModal').style.display='none'" class="folderModalClose">&times;</span>
	<div class="folderText">
	<form action="homepage.php" method="post">

		<input type="text" name="folderName" placeholder="Folder Name">
		<br>
		<br>
		<input type="submit" value="Submit">
		</form>
	</div>
</div>

<!-- Folder Deletion Content -->
<div id="deleteFolderModal" class="deleteFolderForm">
	<span onclick="document.getElementById('deleteFolderModal').style.display='none'" class="fileModalClose">&times;</span>
	<div class="fileText">
	<b>Are you sure you want to delete?</b>
	<form id="delete" action="homepage.php" method="post">
	<input type="hidden" name="delete" value="delete">
	<input type="hidden" id="delFolderName" name="folderName" value="">
	<input type="submit" value="Confirm Delete">
	</form>
	</div>
</div>

<!-- File Deletion Content -->
<div id="deleteFileModal" class="deleteFileForm">
	<span onclick="document.getElementById('deleteFileModal').style.display='none'" class="fileModalClose">&times;</span>
	<div class="fileText">
	<b>Are you sure you want to delete?</b>
	<form id="delete" action="homepage.php" method="post">
	<input type="hidden" name="delete" value="delete">
	<br>
	<input type="text" id="delFolderNameOther" name="folderName" value="">
	<br>
	<input type="text" id="delFileName" name="fileName" value="">
	<br>
	<input type="submit" value="Confirm Delete">
	</form>
	</div>
</div>

<!-- File Creation Content -->
<div id="fileModal" class="fileForm">
	<span onclick="document.getElementById('fileModal').style.display='none'" class="fileModalClose">&times;</span>
	<div class="fileText">
	<form action="homepage.php" method="post" id="fileCreateForm">
	<input type="text" name="fileName" placeholder="File Name...">
	<br>
	<input style="height:50px;" type="text" name="fileDesc" placeholder="Description...">
	<br>
	<pre>
	<textarea form="fileCreateForm" rows="100" cols="30" class="fileCreateText" style="height:200px;" type="text" name="fileSnippit" placeholder="Enter your code here..."></textarea>
	</pre>
	<br>
	<input type="text" name="fileColor" placeholder="Enter color... (ex: aabbcc)">
	<input type="text" id="folderName" name="folderName" value="">
	<input type="submit" value="Submit">
	</form>
	</div>
</div>
<form
<input type="hidden" id="filenamecheese" value="">
</form>

<script>

//Accordion script
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
/* Function openFile
	takes in a filename and opens that file's content
	while closing all of the other file's contents
*/
function openFile(evt, fileName) {
  var i, tabcontent, tablinks, rName;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(fileName).style.display = "block";
  evt.currentTarget.className += " active";
}

function setName(name)
{
	document.getElementById("filenamecheese").value = name;
}

function closeAll() {
  var i, tabcontent, tablinks, rName;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
}

function mostSomething(fileNames)
{
	var i, tabcontent, tablinks, rName;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
	for(i = 0; i < fileNames.length; i++)
	{
		document.getElementById(fileNames[i]).style.display = "block";
		fileNames[i].className += " active";
	}
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").style.display = "block";
var pre= document.querySelector('pre');

//insert a span in front of the first letter.  (the span will automatically close.)
pre.innerHTML= pre.textContent.replace(/(\w)/, '<span>$1');

//get the new span's left offset:
var left= pre.querySelector('span').getClientRects()[0].left;

//move the code to the left, taking into account the body's margin:
pre.style.marginLeft= (-left + pre.getClientRects()[0].left)+'px';

//Function for searching through tabs
function searchbar() {
  // Declare variables
  var input, filter, ul, li, a, i;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  ul = document.getElementsByClassName("panel2");
  // Loop through all list items, and hide those who don't match the search query
  for (j = 0; j < ul.length; j++) {
    count = 0;
    li = ul[j].getElementsByClassName("tablinks");
    for(i = 0; i < li.length; i++){
	a = li[i];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
          li[i].style.display = "block";
	  count++;
	} else {
	  li[i].style.display = "none";
	}
      }
      if( count > 0){
        if( !(ul[j].previousElementSibling.classList.contains("active"))){ //If this accordian is not active, click it  
          ul[j].previousElementSibling.click();
	}
      } else{
	  if(ul[j].previousElementSibling.classList.contains("active")){  //If this accordian is active, click it .
	   ul[j].previousElementSibling.click();
          }
	}
  }
}

//Folder and File Deletion Variables/Scripts


function setFile(file) {
	var fileName = file;
	document.getElementById("delFileName").value = fileName;
}

function setFolder(folder) {
	var folderName = folder;
	document.getElementById("folderName").value = folderName;
	document.getElementById("delFolderName").value = folderName;
	document.getElementById("delFolderNameOther").value = folderName;
}


function darkmode() {
  var element = document.body;
  element.classList.toggle("dark-mode");
}

</script>
<script type="text/javascript">
	//closeAll();
</script>
<?php
function update()
{
	$sqlHope = "UPDATE files SET recent = recent - 1 WHERE user = '$user' AND recent > 0";
	mysqli_query($link, $sqlHope);
	$dummy = document.getElementById("filenamecheese").value;
	$sqlHope2 = "UPDATE files SET recent = 3 WHERE user = '$user' AND name = '$dummy'";
	mysqli_query($link, $sqlHope2);
}
	?>
</body>
</html>
