<?php
session_start();
if(!(isset($_SESSION["loggedin"] ))){
header("location: login.php");
}

require_once "config.php";
$user = $_SESSION["username"];

if(isset($_POST["folderName"])) {
	$folderName = $_POST["folderName"];
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
	 
$sql = "SELECT fname FROM folders WHERE user ='$user'";
    $result = mysqli_query($link, $sql);
?>




<html>
<head>
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

.topnav b:hover {
  background-color: #6EA4BF;
  color: black;
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

/* Style the header */
.header {
  background-color: #C2EFEB;
  padding: 8px;
  text-align: center;
  font-size: 14px;
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
  width: 40%; /* Full width */
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
  <b href="#settings" >Settings</b>
  <a href="logout.php">Logout</a>
</div>
<!-- End top navigation bar-->

<!-- Begin the row and flex elements -->
<div class="row">

<div class="left"><!--This is the left flex, which contains the folders and searchbar -->

<div class="fileMenu">Files
<input type="text" id="search" onkeyup = "searchbar()" placeholder="Search..">
<button onclick="document.getElementById('folderModal').style.display='block'" id="folderModalButton">New Folder...</button>
</div>

<?php
     $arrayParm=array();
     $arrayFetta=array();	
 if (mysqli_num_rows($result) > 0) { //If there are rows in the original Mysql query, then set up arrays and find the files
      $arraye = array();
      $arraya = array();
      $folderJson = array(array());
     // $arrayParm = array();
     // $arrayFetta = array();
      $b = 0;
      /*While there are Folders in the row, get the information from them. */
      while($row = mysqli_fetch_assoc($result)) {
        $param_Tname = $row["fname"];
        $sql1 = "SELECT name, snippit, recent from files where fname = '$param_Tname'";
        $result2 = mysqli_query($link, $sql1);
        if($result2 != false){
        $a = 0;
?>
<!-- Create the folder acordion -->
<button class="accordion"><?php echo $param_Tname ?> </button>
<div class ="panel2">
<div class="tab">
<?php	
        if (mysqli_num_rows($result2) > 0) {
        while($row = mysqli_fetch_assoc($result2)) {
	/* These are the arrays that we use to create buttons with */ 
	$arraye[$a] = $row["name"];
	$arraya[$a] = $row["snippit"]; 
	/* These are cheese arrays I am using to avoid problems */
	$arrayParm[$b] = $row["name"];
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
        echo'<button class="tablinks" onclick="openFile(event, '.$taxes.$str.$b.$taxes.')" id="defaultOpen">'.$row["name"].' </button>';  /* Here the variable $b is to avoid non-unique names */
 	}else{
	 echo'<button class="tablinks" onclick="openFile(event, '.$taxes.$str.$b.$taxes.')">'.$row["name"].' </button>';
	
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

<!-- End left flex and mvoe to right side of the screen -->
<div class="right">
<div class = "header">
<h2>FreedomFlow</h2>
</div>
<?php
//For each folder in the array 
for($i = 0; $i < count($arrayParm); $i++){
?>
<!-- make the content -->
<div id = <?php echo $arrayParm[$i].$i; ?> class="tabcontent">
<h3><?php echo $arrayParm[$i]; ?></h3> 
<pre>
   <?php echo $arrayFetta[$i];?>
</pre>
</div>
<?php }?>

</div>
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
  var i, tabcontent, tablinks;
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

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").style.display = "block";
var pre= document.querySelector('pre');

//insert a span in front of the first letter.  (the span will automatically close.)
pre.innerHTML= pre.textContent.replace(/(\w)/, '<span>$1');

//get the new span's left offset:
var left= pre.querySelector('span').getClientRects()[0].left;

//move the code to the left, taking into account the body's margin:
pre.style.marginLeft= (-left + pre.getClientRects()[0].left)+'px';

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
  //  console.log(a.innerHTML.toUpperCase());
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
          console.log("we good " + a.innerHTML);
          li[i].style.display = "block"; 
	  count++; 
	} else { 
	  li[i].style.display = "none";  
	}
      }
      if( count > 0){
        if( !(ul[j].previousElementSibling.classList.contains("active"))){
          ul[j].previousElementSibling.click();
	}
      } else{
	  if(ul[j].previousElementSibling.classList.contains("active")){
	   ul[j].previousElementSibling.click();
          }	
	}
  }
}

</script>
</body>
</html>
