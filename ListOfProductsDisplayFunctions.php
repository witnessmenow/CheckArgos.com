<?php

// -------------------
// --- PHP Imports ---
// -------------------
require_once('Common/Common.php');
require_once('ItemParser.php');

function drawListOfStockTable($products, $store)
{
	echo 	'<table width="800" id="clearance" class="sortable">
				<tr>
					<th>Product Name</th>
					<th>Price</th>
					<th>Save</th>
					<th>Product ID</th>
					<th>Stock</th>
				</tr>';


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
			
		$productUrl = "http://www.argos.ie/static/Product/partNumber/".$valProductId.".htm";
		$checkAllStoresUrl = "http://www.checkargos.com/index.php?productId=".$valProductId;


		echo 	"<tr id=\"product".$valProductId."\">
						<td><a title=\"Open product page on Argos.ie\" href=\"".$productUrl."\" target=\"_blank\">".$productName."</a></td>
						<td title=\"Was: €".$productWasPrice."\">€".$productPrice."</td>
						<td title=\"Was: €".$productWasPrice."\">".$precentageSaving."%</td>
						<td><a title=\"Check stock in all Stores\" href=\"".$checkAllStoresUrl."\" target=\"_blank\">".$productId."</a></td>
						<td id=\"td".$valProductId."\"><span id=\"span".$valProductId."\"></td>
				</tr>";
				
		echo '<SCRIPT LANGUAGE="javascript">';
		echo 'updateStock("'.$valProductId.'","'.$store.'");';
		echo '</SCRIPT>';
	}

	echo 	"</table>";
}

?>