<?php

// -------------------
// --- PHP Imports ---
// -------------------

require_once('ItemParser.php');
require_once('Common/Common.php');

function searchProducts($searchString, $numberOfItems, $offSet, $sort)
{
	//http://www.argos.ie/webapp/wcs/stores/servlet/Search?storeId=10152&langId=111&q=GAME&pp=20&s=Relevance&canned_results_trigger=%28free_text%3D%3D%28+GAME%29%29&p=21
	//http://www.argos.ie/webapp/wcs/stores/servlet/Search?storeId=10152&langId=111&q=CAR&pp=20&s=Relevance&p=21
	
	$searchString = convertSearchString($searchString);
	
	$url = "http://www.argos.ie/webapp/wcs/stores/servlet/Search?storeId=10152&langId=111&q=". $searchString ."&pp=". $numberOfItems ."&s=". $sort ."&p=". $offSet;
	
	$htmlCode = file_get_contents("$url");
	
	$products = getItemsArray($htmlCode);
	
	displayProductsAsJson($products);
}

function advancedSearchProducts($searchString, $sectionSelected, $sectionNumber, $minPrice, $maxPrice, $numberOfItems, $offSet, $sort)
{
	$searchString = convertSearchString($searchString);
	
	$url = "http://www.argos.ie/webapp/wcs/stores/servlet/Search?pp=". $numberOfItems ."&s=". $sort ."&storeId=10152&langId=111&q=". $searchString;
	if ($sectionSelected != "All+Sections")
	{
		$url = $url ."&c_1=1|category_root|".$sectionSelected."|".$sectionNumber;
	}
	$url = $url ."&r_001=2|Price|".$minPrice."+%3C%3D++%3C%3D+".$maxPrice."|2";
	
	$htmlCode = file_get_contents("$url");
	
	$products = getItemsArray($htmlCode);
	
	return $products;
}


function displayProductsAsJson($products)
{
	$productDetails = array();

	foreach ($products as $singleProduct)
	{
		$productName = getName($singleProduct);
		$productPrice = getCurrentPrice($singleProduct);
		$productWasPrice = getWasPrice($singleProduct, $productPrice);
		$productId = getId($singleProduct);
		$valProductId = validateProductId($productId);
		if ($productWasPrice)
			$precentageSaving = number_format(((1-($productPrice/$productWasPrice))*100), 0);
		else
			$precentageSaving = 0;
			

	

		array_push($productDetails, array( 	"productName" => $productName ,
											"productPrice" => $productPrice,
											"productWasPrice" => $productWasPrice,
											"precentageSaving" => $precentageSaving,
											"productId" => $valProductId));
	}
	
	echo json_encode($productDetails);
}

function convertSearchString($searchString)
{
	$newSearchString = str_replace (' ', "+", $searchString);
	
	$newSearchString = strtoupper($newSearchString);
	
	return $newSearchString;
}

?>