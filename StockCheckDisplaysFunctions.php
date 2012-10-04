<?php

// -------------------
// --- PHP Imports ---
// -------------------
require_once('Common/Common.php');

function displayStockCheckForm($productId)
{
	$actionUrl = "StockCheckPage.php";

	echo '<font face="Arial" size="6">Stock Checker:</font>';
	echo "<br />";
	echo 'Enter a product ID to check the stock levels throughout the country.';
	echo "<br />";
	echo "<br />";

	echo '	<form name="input" action="'.$actionUrl.'" method="get">
				Product ID: <input type="text" name="productId" value="'.$productId.'" />
				<input type="submit" value="Submit" />
			</form>';
			
	echo "<br />";
}

function displayStockTableIreland($productId)
{
	echo '
		<table width="800" id="stock" border="1">
			<tr>
				<th>Store Name</th>
				<th>Stock Status</th>
				<th>&nbsp;</th>
				<th>Store Name</th>
				<th>Stock Status</th>
			</tr>';
					
	//Irish Stores
	$i=0;
	
	$stores = getIrishStores();
	while (list($storeName1, $storeId1) = each($stores))
	{
			list($storeName2, $storeId2) = each($stores);
		
		$i=$i+1;
		if ($i%2)
		{
			//not alt table row
			$altText="";
		}
		else
		{
			$altText=' class="alt"';
		}
			
		echo '<tr'.$altText.'>';
				
			echo '<td>'.$storeName1.'</td>';
			echo '<td id="status'.$storeId1.'"> <span id="'.$storeId1.'"></span></td>';
			
			echo '<SCRIPT LANGUAGE="javascript">';
			echo 'updateStock("'.$productId.'","'.$storeId1.'");';
			echo '</SCRIPT>';
			
			//Check that we have two valid storeIds ... damn you wexford!
			if ($storeId2 != "")
			{
				echo '<td></td>';
				
				
				echo '<td>'.$storeName2.'</td>';
				echo '<td id="status'.$storeId2.'"> <span id="'.$storeId2.'"></span></td>';
				
				echo '<SCRIPT LANGUAGE="javascript">';
				echo 'updateStock("'.$productId.'","'.$storeId2.'");';
				echo '</SCRIPT>';
			}

			
		echo '</tr>';
	
	}
	
	echo '</table>';
}

function displayStockTableNI($productId)
{
	echo '
		<table border="1">
			<tr>
				<td><b>Store Name</b></td>
				<td><b>Stock Status</b></td>
				<td>&nbsp;&nbsp;&nbsp;</td>
				<td><b>Store Name</b></td>
				<td><b>Stock Status</b></td>
			</tr>';
			
	
	$storesNI = getNIStores();			
	while (list($storeName1, $storeId1) = each($storesNI))
	{
			list($storeName2, $storeId2) = each($storesNI);
			
		echo '<tr>';
				
			echo '<td>'.$storeName1.'</td>';
			echo '<td id="status'.$storeId1.'"> <span id="'.$storeId1.'"></span></td>';
			
			echo '<SCRIPT LANGUAGE="javascript">';
			echo 'updateStockNI("'.$productId.'","'.$storeId1.'");';
			echo '</SCRIPT>';
			
			if ($storeId2 != "")
			{
				echo '<td>&nbsp;&nbsp;&nbsp;</td>';
				
				
				echo '<td>'.$storeName2.'</td>';
				echo '<td id="status'.$storeId2.'"> <span id="'.$storeId2.'"></span></td>';
				
				echo '<SCRIPT LANGUAGE="javascript">';
				echo 'updateStockNI("'.$productId.'","'.$storeId2.'");';
				echo '</SCRIPT>';
			}

			
		echo '</tr>';
	}
	
	echo '</table>';
}

?>