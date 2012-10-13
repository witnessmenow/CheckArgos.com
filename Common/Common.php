<?php

/*

PHP on server has parse ini blocked, revisit

function getIrishStores()
{
	$irishStoresFile = "IrishStores.data";
	
	$irishStoresArray = parse_ini_file($irishStoresFile);
	//print_r($irishStoresArray);
	return $irishStoresArray;
	
}


function getNIStores()
{
	$niStoresFile = "NIStores.data";
	
	$niStoresFile = parse_ini_file($niStoresFile);
	//print_r($niStoresFile);
	return $niStoresFile;
	
}
*/

function writeToDataFile($fileName, $productId, $searchType)
{
	
	$infoFile = fopen($fileName,"a+");
	fputcsv ($infoFile , array($productId, time(), $searchType), ',');
	fclose ($infoFile);
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

function getIrishStores()
{
	$stores = array(
					"Arklow (Extra)" => 4101,
					"Ashbourne Retail Park" => 943,
					"Athlone" => 262,
					"Blanchardstown West End" => 669,
					"Carlow (Extra)" => 4130,
					"Castlebar" => 807,
					"Cavan (Extra)" => 814,
					"Clonmel (Extra)" => 4214,
					"Cork Mahon (Extra)" => 4113,
					"Cork Queens Old Castle" => 45,
					"Cork Retail Park" => 801,
					"Drogheda (Extra)" => 875,
					"Dun Laoghaire" => 200,
					"Dundalk Retail Park (Extra)" => 931,
					"Dundrum" => 817,
					"Galway" => 547,
					"Ilac Centre Dublin" => 394,
					"Jervis Street Dublin" => 397,
					"Kilkenny" => 201,
					"Killarney (Extra)" => 899,
					"Letterkenny (Extra)" => 793,
					"Liffey Valley (Extra)" => 687,
					"Limerick Childers Road (Extra)" => 915,
					"Limerick Cruises Street" => 393,
					"Limerick The Crescent" => 583,
					"Longford (Extra)" => 880,
					"Monaghan (Extra)" => 945,
					"Naas (Extra)" => 4218,
					"Navan" => 832,
					"Nutgrove" => 392,
					"Omni Park Dublin (Extra)" => 4150,
					"Portlaoise (Extra)" => 4125,
					"Sligo (Extra)" => 4146,
					"St Stephens Green Dublin" => 584,
					"Swords (Extra)" => 581,
					"Tallaght" => 395,
					"Tralee (Extra)" => 11,
					"Tullamore (Extra)" => 879,
					"Waterford" => 396,
					"Wexford (Extra)" => 826
					);
					
	return $stores;
}

function loadIrishStores()
{
	$storePageUrl = "http://www.argos.ie/webapp/wcs/stores/servlet/ArgosStoreLocatorDB?includeName=StoreLocator&langId=111&storeId=10152"; 
	$storeRegex = '/class="storeTitle" id="STORE(\d{3,4})">\s*<span>\s*([\D^]*)/';
	
	$storePageHTML = file_get_contents("$storePageUrl");
	//echo $storePageHTML;
	
	//preg_match_all ('/class="storeTitle" id="STORE([0-9]{3,4})/', $storePageHTML, $idArray);
	preg_match_all ('/<span>\s*([\D^]*)\s*([0-9]{2,4})\s*<\/span>/', $storePageHTML, $match);
	
	$storeArray = array();
	//print_r ($match);
	for ($i = 0; $i< count($match[1]); $i++)
	{
		$storeName = $match[1][$i];
		$storeId = $match[2][$i];
		//Todo find Extra stores
		
		array_push($storeArray, array("name" => $storeName, "id" => $storeId));
	}
	
	echo json_encode($storeArray);
	
}

function getNIStores()
{
	$storesNI = array(
						"Belfast Roses Court (Extra)" => 484,
						"Belfast Castle Court (Extra)" => 840,
						"Newtownabbey (Extra)" => 537,
						"Newtownards" => 792,
						"Bangor Flagship Centre" => 213,
						"Belfast Royal Avenue" => 217,
						"Belfast Connswater" => 841,
						"Lisburn Square" => 586,
						"Lisburn Sprucefield ((Extra))" => 835,
						"Bangor Bloomfield Centre" => 838
						);
						
	return $storesNI;
}

?>