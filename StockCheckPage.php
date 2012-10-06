<html>
<head>

<style type="text/css">
#stock
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
border-collapse:collapse;
empty-cells: hide;
}
#stock td, #stock th 
{
font-size:1em;
border:1px solid #2CBED8;
padding:3px 7px 2px 7px;
}
#stock th 
{
font-size:1.1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#09B2D1;
color:#ffffff;
}
#stock tr.alt td 
{
color:#000000;
background-color:#EAF2D3;
}
</style>

<script src="js/mainStockCheck.js"></script>


<script src="js/googleAnalytics.js" type="text/javascript"></script>

</head>
<body>

<?php

// -------------------
// --- PHP Imports ---
// -------------------

require_once('Common/Layouts.php');
require_once('Common/Common.php');
require_once('StockCheckFunctions.php');
require_once('StockCheckDisplaysFunctions.php');

//$checkNI = $_GET["checkNI"];
$productId = $_GET["productId"];
$productId = validateProductId($productId);

$infoFileName = "data.csv";

displayHeader();

displayStockCheckForm($productId);

if ($productId == "")
{
	echo "Please enter a product ID";
	echo "<br />";
}
else
{
	$productExist = displayItemInfo($productId);
	
	if ($productExist)
	{
		displayStockTableIreland($productId);
	}
}

displayFooter();

?>