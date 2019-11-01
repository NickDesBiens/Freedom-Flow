<?php
session_start();

require_once "config.php";

$sql = "SELECT fname FROM folders";
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

.panel {
  padding: 0 18px;
  display: none;
  background-color: #ECFEE8;
  overflow: hidden;
}
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

.fileMenu{
 background-color: #4E0066;
  padding: 18px;
  text-align: Left;
  font-size: 18px;
  color: white; 
}

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
<body style= "background:#FFFCE8">
<div class="topnav">
  <a class="active" href="#home">Home</a>
  <a href="#contact">Contact</a>
  <a href="#about">About</a>
  <b href="#settings">Settings</b>
</div>

<div class="row">
<div class="left">
<div class="fileMenu">Files
<input type="text" id="search" onkeyup="search()" placeholder="Search..">
</div>
<?php
 if (mysqli_num_rows($result) > 0) {
      $arraye = array();
      $arraya = array();
      $folderJson = array(array());
      $arrayParm = array();
      $arrayFetta = array();
      $b = 0;
      //While there are exams in the row, list the names and create buttons for them if they havent been taken yet.
      while($row = mysqli_fetch_assoc($result)) {
        $param_Tname = $row["fname"];
        $sql1 = "SELECT name, snippit from files where fname = '$param_Tname'";
        $result2 = mysqli_query($link, $sql1);
        if($result2 != false){
        $a = 0;
?>
<button class="accordion"><?php echo $param_Tname ?> </button>
<div class ="panel2">
<div class="tab">
<?php

        if (mysqli_num_rows($result2) > 0) {
        while($row = mysqli_fetch_assoc($result2)) {
	$arraye[$a] = $row["name"];
	$arraya[$a] = $row["snippit"];
	$arrayParm[$b] = $row["name"];
	$arrayFetta[$b] = $row["snippit"];
//	if( //tHe array where I am trying to store data does not exits
	if(! (isset($incomingFolder))){
	$incomingFolder = array($param_Tname => array('filename' => $row["name"], 'snippit' => $row["snippit"] ));
	}
	else{
	$tempArray = array($param_Tname => array('filename' => $row["name"], 'snippit' => $row["snippit"] ));
	$incomingFolder[$param_Tname][$a] = $tempArray;
 	}
	$death = "'";
	$taxes = "'";
	$str = htmlentities($row["name"]);
        echo'<button class="tablinks" onclick="openCity(event, '.$death.$str.$b.$taxes.')">'.$row["name"].' </button>';
 	$a++; 
	$b++;
        }
	$folderJson = array_merge($folderJson, $incomingFolder);
      }
	}
?>	
	
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
<!--<button class="accordion">File 2</button>
<div class="panel">
</div>

<button class="accordion">File 3</button>
<div class="panel">
</div>
</div>
-->
<div class="right">
<div class = "header">
<h2>FreedomFlow</h2>
</div>
<!--
<div id="Forloop" class="tabcontent">
  <h3>Forloop</h3>
<pre>
   for (int i = 0; i &lt; 5; i++) {
     System.out.println(i);
   }
</pre>
</div>
-->
<?php

for($i = 0; $i < count($arrayParm); $i++){
?>
<div id = <?php echo $arrayParm[$i].$i; ?> class="tabcontent">
<h3><?php echo $arrayParm[$i]; ?></h3>
<pre>
   <?php echo $arrayFetta[$i];?>
</pre>
</div>
<?php }?>
<!--
<div id="Quicksort" class="tabcontent">
  <h3>Quicksort</h3>
 <pre>
	quickSort(arr[], low, high)
	{
	    if (low &lt; high)
	    {
        	/* pi is partitioning index, arr[pi] is now
           	at right place */
        	pi = partition(arr, low, high);
        	quickSort(arr, low, pi - 1);  // Before pi
     	 	quickSort(arr, pi + 1, high); // After pi
    	    }
	}
</pre>
</div>

<div id="Array" class="tabcontent">
  <h3>Array</h3>
  <pre>String[] cars = {"Volvo", "BMW", "Ford", "Mazda"};
System.out.println(cars[0]);
// Outputs Volvo
</pre>
</div>
-->
</div>
</div>

</div>
<script>
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
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
var pre= document.querySelector('pre');

//insert a span in front of the first letter.  (the span will automatically close.)
pre.innerHTML= pre.textContent.replace(/(\w)/, '<span>$1');

//get the new span's left offset:
var left= pre.querySelector('span').getClientRects()[0].left;

//move the code to the left, taking into account the body's margin:
pre.style.marginLeft= (-left + pre.getClientRects()[0].left)+'px';
</script>
</body>
</html>
