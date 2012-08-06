<?php

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

function validateProductId($productId)
{
	//first lets strip the "/" out
	$productId = str_replace ('/', "", $productId);
	
	//lets strip spaces out
	$productId = str_replace (' ', "", $productId);

	//TODO Validate characters and length(7?).
	
	return $productId;
}

?>