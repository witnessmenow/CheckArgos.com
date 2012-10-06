
<?php

// -------------------
// --- PHP Imports ---
// -------------------
require_once('Common/Common.php');

function getStockStatusNI($productId, $storeId)
{
	$postCode = "BTA1AA";
	$NiStockUrl = "http://www.argos.co.uk/webapp/wcs/stores/servlet/ISALTMStockAvailability?langId=-1&storeId=10001&checkStock=true&isStoreListPage=true&backTo=product&partNumber_1=".$productId."&postCode=".$postCode."&storeNo_1=".$storeId."&viewTaskName=ISALTMAjaxResponseView";
	
	$response = file_get_contents("$irishStockUrl");
	
	//response contains info about home delivery first, need to chop out.
	$splitQuery = explode ('<td class=\"storePickup\">', $response);
	
	$stockStatus = extractStockDetails ($splitQuery[1]);
	
	return $stockStatus;
	
}

function getStockStatusIreland($productId, $storeId)
{
	$irishStockUrl = "http://www.argos.ie/webapp/wcs/stores/servlet/ISALTMStockAvailability?storeId=10152&langId=111&partNumber_1=".$productId."&checkStock=true&backTo=product&storeSelection=".$storeId."&viewTaskName=ISALTMAjaxResponseView";
	
	$response = file_get_contents("$irishStockUrl");
	
	$pos = strpos($response, "storePickup2");
	if ($pos !== false)
	{
		//Containts two store stock info
		$splitQuery = explode('<td class=\"storePickup2\">', $response);
		$stockStatus = extractStockDetails ($splitQuery[0]);
	}
	else
	{
		$stockStatus = extractStockDetails ($response);
	}
	
	return $stockStatus;
	
}
			
function extractStockDetails ($query)
{

	global $stockDetail;
	$pos = strpos($query, "inStock");
	//echo $pos;
	
			
	if ($pos !== false)
	{
		//item is in stock!
		$stockStatus = 'In stock';
				
		if ($stockDetail)
		{
			$stockStatus = $stockStatus + ':  ' + get_stock_quantity($query);
		}

		return $stockStatus;
	}

	// Can the Item be ordered?
	$pos = strpos($query, "canBeOrdered");
	if ($pos !== false)
	{
		//item can be ordered!
		$stockStatus =  'Item can be ordered';
		return $stockStatus;
	}
	
	//NI Can the Item be ordered?
	$pos = strpos($query, "csoInstock");
	if ($pos !== false)
	{
		//item can be ordered!
		$stockStatus =  'Item can be ordered';
		return $stockStatus;
	}

	//Is the item out of stock?
	$pos = strpos($query, "outOfStock");
	if ($pos !== false)
	{
		//item is out of stock!
		$stockStatus =  'Item is out of stock';
		return $stockStatus;
	}
	$stockStatus = 'Unkown Status';

	return $stockStatus;
}

function get_stock_quantity($response)
{

	preg_match ('/([0-9]*) left to/', $response, $match);
	if ($match[1] < 5) 
		return $match[1];
	else
		return "5 +";

}
	
	

?>
</body>
</html>
