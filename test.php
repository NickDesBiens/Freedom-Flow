<?php
	$sqlHope = "UPDATE files SET recent = recent - 1 WHERE user = '$user' AND recent > 0";
	mysqli_query($link, $sqlHope);
	$dummy = document.getElementById("filenamecheese").value;
	$sqlHope2 = "UPDATE files SET recent = 3 WHERE user = '$user' AND name = '$dummy'";
	mysqli_query($link, $sqlHope2);
?>
