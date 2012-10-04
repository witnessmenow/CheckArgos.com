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


<script src="js/googleAnalytics.js" type="text/javascript"></script>

</head>
<body>


<?php

// -------------------
// --- PHP Imports ---
// -------------------

require_once('Common/Layouts.php');
require_once('Common/Common.php');
require_once('StockCheckDisplaysFunctions.php');

//Maybe im wrong, seems to be working without it, will leave this here incase it needs to be re-enabled
//It would appear argos does not like people using their pictures! Faking a user agent seems to be the only way of getting it to show up
//ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');


//$checkNI = $_GET["checkNI"];
$productId = $_GET["productId"];

if ($productId != "")
{
	//User has passed a product id, redirect them to the stock check page.
	$redirectUrl = "StockCheckPage.php?productId=". $productId;
	header( 'Location: '.$redirectUrl ) ;
}


displayHeader();

displayStockCheckForm("");
echo "Please enter a product ID";
echo "<br />";

	
displayFooter();

	
	

?>
</body>
</html>
