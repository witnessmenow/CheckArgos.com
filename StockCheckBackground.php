
<?php

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
	$postCode = "BTA1AA";
	$response = file_get_contents("http://www.argos.co.uk/webapp/wcs/stores/servlet/ISALTMStockAvailability?langId=-1&storeId=10001&checkStock=true&isStoreListPage=true&backTo=product&partNumber_1=".$productId."&postCode=".$postCode."&storeNo_1=".$storeId."&viewTaskName=ISALTMAjaxResponseView");	
	
	//response contains info about home delivery first, need to chop out.
	$splitQuery = explode ('<td class=\"storePickup\">', $response);
	
	searchText ($splitQuery[1]);
}
//Irish Store
else
{				
	$response = file_get_contents("http://www.argos.ie/webapp/wcs/stores/servlet/ISALTMStockAvailability?storeId=10152&langId=111&partNumber_1=".$productId."&checkStock=true&backTo=product&storeSelection=".$storeId."&viewTaskName=ISALTMAjaxResponseView");
	
	$pos = strpos($response, "storePickup2");
	if ($pos !== false)
	{
		//Containts two store stock info
		$splitQuery = explode('<td class=\"storePickup2\">', $response);
		searchText ($splitQuery[0]);
	}
	else
	{
		searchText ($response);
	}
	
}
			
function searchText ($query)
{

	global $stockDetail;
	$pos = strpos($query, "inStock");
	//echo $pos;
			
	if ($pos !== false)
	{
		//item is in stock!
		echo 'In stock:  ';
				
		if ($stockDetail)
		{
			echo get_stock_quantity($query);
		}

		return;
	}

	// Can the Item be ordered?
	$pos = strpos($query, "canBeOrdered");
	if ($pos !== false)
	{
		//item can be ordered!
		echo 'Item can be ordered';
		return;
	}
	
	//NI Can the Item be ordered?
	$pos = strpos($query, "csoInstock");
	if ($pos !== false)
	{
		//item can be ordered!
		echo 'Item can be ordered';
		return;
	}

	//Is the item out of stock?
	$pos = strpos($query, "outOfStock");
	if ($pos !== false)
	{
		//item is out of stock!
		echo 'Item is out of stock';
		return;
	}
	echo 'Unkown Status';

	return;
}

function validateProductId($productId)
{
	//first lets strip the "/" out
	$productId = str_replace ('/', "", $productId);
	
	//lets strip spaces out
	$productId = str_replace (' ', "", $productId);

	//TODO Validate characters and length(7?).
	
	return $productId;
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
