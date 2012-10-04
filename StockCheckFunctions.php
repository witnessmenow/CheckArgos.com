<?php

function displayItemInfo($productId)
{
	$infoFileName = "data.csv";

	$productpage = file_get_contents("http://www.argos.ie/webapp/wcs/stores/servlet/ArgosAddByPartNumber?partNum_1=".$productId."&searchBean=Search+Terms+%5B".$productId."%5D+Search+Scope+%5B0%5D&langId=111&partNumber=".$productId."&storeId=10152");
	
	$pos = strpos($productpage, "Sorry, we are unable to find the catalogue number(s) highlighted in your list.");
	if ($pos !== false)
	{
			//item does not exist!
			echo "No item found matching product ID\n";
			echo "<br />";
			
			require_once("footer.php");
			echo'</center>';
			exit;
	}
	
	$infoFile = fopen($infoFileName,"a+");
	fputcsv ($infoFile , array($productId, time()), ',');
	fclose ($infoFile);
	
	
	
	$productUrl = "http://www.argos.ie/static/Product/partNumber/".$productId.".htm";
	$productPage = file_get_contents("$productUrl");
	$productPageTitle = get_page_title($productPage);
	$productTitle = explode ("at Argos.ie", $productPageTitle);
	$productPrice = get_product_price($productPage);
	$productImage = "http://www.argos.ie".get_product_image($productPage);
	echo '	<table width="800">
				<tr>
					<td align="center" width="375">
						<a href="'.$productUrl.'">'.$productTitle[0].'at Argos.ie - &euro;'.$productPrice.'</a>
					</td>
					<td align="center">
						<img src="'.$productImage.'" alt="'.$productTitle[0].'" />
					</td>
				</tr>
			</table>';
			
	echo "<br /><br />";
	
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
	return $match[1];
}

?>