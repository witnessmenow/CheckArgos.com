
<?php

// -------------------
// --- PHP Imports ---
// -------------------

require_once('StockCheckBackgroundFunctions.php');

//set stockDetail to 0 if you dont want to show how many items in stock
$stockDetail = 1;

$productId = $_GET["productId"];
$productId = validateProductId($productId);

$storeId = $_GET["storeId"];
//we should validate store ID
//$storeId = validateProductId($storeId);

$isNI = $_GET["NI"];

$check = $_GET["checkProduct"];

if ($isNI == "true")
{
	echo getStockStatusNI($productId, $storeId);
}
//Irish Store
else
{				
	echo getStockStatusIreland($productId, $storeId);
}

?>

