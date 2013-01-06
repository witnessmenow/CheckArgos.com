<?php

function displayItemInfo($productId)
{
	$infoFileName = "data.csv";

	$productPage = getProductHtmlCode($productId);
	
	if (!doesProductExist($productPage))
	{
		//item does not exist!
		echo "No item found matching product ID\n";
		echo "<br />";
		
		return false;
	}
	
	$infoFile = fopen($infoFileName,"a+");
	fputcsv ($infoFile , array($productId, time()), ',');
	fclose ($infoFile);
	
	$productUrl = getProductUrl($productId);
	
	$productPageTitle = get_page_title($productPage);
	$productTitle = getProductName($productPageTitle);
	$productPrice = get_product_price($productPage);
	$productImage = get_product_image($productPage);
	echo '	<table width="800">
				<tr>
					<td align="center" width="375">
						<a href="'.$productUrl.'">Buy '.$productTitle.'at Argos.ie - &euro;'.$productPrice.'</a>
					</td>
					<td align="center">
						<img src="'.$productImage.'" alt="'.$productTitle.'" />
					</td>
				</tr>
			</table>';
			
	echo "<br /><br />";
	
	return true;
	
}

function getProductHtmlCode($productId)
{
	$productUrl = getProductUrl($productId);
	return file_get_contents("$productUrl");
}

function getProductUrl($productId)
{
	$productUrl = "http://www.argos.ie/static/Product/partNumber/".$productId.".htm";
	return $productUrl;
}

function doesProductExist($productPage)
{
	$pos = strpos($productPage, "Sorry, we are unable to find the catalogue number(s) highlighted in your list.");
	if ($pos !== false)
	{
		return false;
	}
	else
	{
		return true;
	}
	
}

function getProductName($productPageTitle)
{
	$tempArray = explode ("at Argos.ie", $productPageTitle);
	$productName = $tempArray[0];
	return str_replace("Buy ", "", $productName);
}

function displayNIItemInfo($productId)
{

	echo "<br />";
	echo "<br />";

	echo '<b>Northern Irish Stores:</b>';

	echo "<br />";
	
	$productPage = "http://www.argos.co.uk/static/Product/partNumber/".$productId.".htm";
	//for the moment lets just re-use the product name from argos.ie
	echo '<a href="'.$productPage.'">'.$productTitle[0].'at Argos.co.uk</a>';
	echo "<br /><br />";
}

function get_page_title($data)
{

	// Get <title> line
	preg_match ("/<title>([^`]*?)<\/title>/", $data, $match);
	return $match[1];


}

function get_product_price($data)
{
	// Get price
	preg_match ("/\"price\">\s*\&euro\;(.*?)\s/", $data, $match);
	return $match[1];

}

function get_product_image($data)
{
	// Get Image
	preg_match ('/id="mainimage" src="(.*?)"/', $data, $match);
	return "http://www.argos.ie".$match[1];
}

?>