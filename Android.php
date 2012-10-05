<?php

// -------------------
// --- PHP Imports ---
// -------------------

require_once('Common/Common.php');
require_once('StockCheckFunctions.php');

//Maybe im wrong, seems to be working without it, will leave this here incase it needs to be re-enabled
//It would appear argos does not like people using their pictures! Faking a user agent seems to be the only way of getting it to show up
//ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');

$functionCall = $_GET["function"];
$productId = $_GET["productId"];

if ($functionCall == "info")
{
	if ($productId != "")
	{
		$productPage = getProductHtmlCode($productId);
		if (doesProductExist($productPage))
		{
			$productPageTitle = get_page_title($productPage);
			$productName = getProductName($productPageTitle);
			$productPrice = get_product_price($productPage);
			$productImage = get_product_image($productPage);
			
			productInfoJson($productId,
							$productName,
							$productPrice,
							$productImage);
		}
	}

	return;
}
else if ($functionCall == "getStores")
{
	loadIrishStores();
}


function productInfoJson(	$productId,
							$productName,
							$productPrice,
							$productImage)
{
	$productInfo = array( 	"id" => $productId,
							"name" => $productName,
							"price" => $productPrice,
							"imageUrl" => $productImage);
							
	echo json_encode($productInfo);
}
?>