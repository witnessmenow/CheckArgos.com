<?php

function getItemsArray($htmlCode)
{	
	//Get an array of products
	$items = explode ("<li class=\"producttitle\">", $htmlCode);
	$products = array_slice($items, 1);
	
	return $products;
}

function getTotalNumberProducts($htmlCode)
{
	$items = explode ("<li class=\"producttitle\">", $htmlCode);
	preg_match('/of\s\<span\>([0-9]*)<\/span\>/', $items[0], $match);
	$totalNumProducts = $match[1];
	
	return $totalNumProducts;
}

function getCurrentPrice ($productText)
{
	preg_match('/class=\"price footnote\"\>\s*\&euro;(.*)\\s*\<span/', $productText, $match);
	if ($match[1] == "")
		preg_match('/class=\"price \"\>\s*\&euro;(.*)\\s*\<\/li/', $productText, $match);
	
	return $match[1];
}

function getWasPrice ($productText, $currentPrice)
{
	preg_match('/class=\"wasprice\"\>Was\s\&euro;(.*)\\s*\<\/li/', $productText, $match);	
	if ($match[1] != "")
		return $match[1];
	else
		return 0;
}

function getId ($productText)
{
	preg_match('/class=\"partnum\"\>(.*)\<\/span\>/', $productText, $match);
	return $match[1];
}

function getName ($productText)
{
	preg_match('/class=\"description\"\>\s*\<a.*>(.*)\<\/a\>/', $productText, $match);
	return $match[1];
}

?>