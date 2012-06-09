<html>
<head>

<style type="text/css">
#stock
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
border-collapse:collapse;
empty-cells: hide;
}
#stock td, #stock th 
{
font-size:1em;
border:1px solid #2CBED8;
padding:3px 7px 2px 7px;
}
#stock th 
{
font-size:1.1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#09B2D1;
color:#ffffff;
}
#stock tr.alt td 
{
color:#000000;
background-color:#EAF2D3;
}
</style>

<script type="text/javascript">

//Using a method for handling multiple XMLHTTPREquests as found
//here: http://drakware.com/?e=3

var xmlreqs = new Array();

function CXMLReq(freed)
{
	this.freed = freed;
	this.xmlhttp = false;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		this.xmlhttp=new XMLHttpRequest();
	}
	else
	{
		this.xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
}

function updateStock(productId, storeId)
{
	var pos = -1;
	for (var i=0; i<xmlreqs.length; i++) 
	{
		if (xmlreqs[i].freed == 1) 
		{ 
		pos = i; break; 
		}
	}
	if (pos == -1) 
	{ 
		pos = xmlreqs.length; xmlreqs[pos] = new CXMLReq(1); 
	}
	if (xmlreqs[pos].xmlhttp) 
	{
		xmlreqs[pos].freed = 0;
		xmlreqs[pos].xmlhttp.open("GET",'stockCheck.php?NI=false&productId='+productId+'&storeId='+storeId,true);
		xmlreqs[pos].xmlhttp.onreadystatechange = function()
		{
			if (typeof(xmlhttpChange) != 'undefined') 
			{ 
				xmlhttpChange(pos, storeId); 
			}
		}
		
		xmlreqs[pos].xmlhttp.send();
	}
}

function updateStockNI(productId, storeId)
{
	var pos = -1;
	for (var i=0; i<xmlreqs.length; i++) 
	{
		if (xmlreqs[i].freed == 1) 
		{ 
		pos = i; break; 
		}
	}
	if (pos == -1) 
	{ 
		pos = xmlreqs.length; xmlreqs[pos] = new CXMLReq(1); 
	}
	if (xmlreqs[pos].xmlhttp) 
	{
		xmlreqs[pos].freed = 0;
		xmlreqs[pos].xmlhttp.open("GET",'stockCheck.php?NI=true&productId='+productId+'&storeId='+storeId,true);
		xmlreqs[pos].xmlhttp.onreadystatechange = function()
		{
			if (typeof(xmlhttpChange) != 'undefined') 
			{ 
				xmlhttpChange(pos, storeId); 
			}
		}
		
		xmlreqs[pos].xmlhttp.send();
	}
}
  
function xmlhttpChange(pos, storeId) 
{
	if (typeof(xmlreqs[pos]) != 'undefined' && xmlreqs[pos].freed == 0 && xmlreqs[pos].xmlhttp.readyState == 4) 
	{
		if (xmlreqs[pos].xmlhttp.status == 200 || xmlreqs[pos].xmlhttp.status == 304) 
		{
			var elementId = "status";
			
			document.getElementById(storeId).innerHTML=xmlreqs[pos].xmlhttp.responseText;
			if (xmlreqs[pos].xmlhttp.responseText.search("In stock") >= 0)
			{
				document.getElementById(elementId.concat(storeId)).style.background= '#A7C942';
			}
			else if (xmlreqs[pos].xmlhttp.responseText.search("ordered") >= 0)
			{
				document.getElementById(elementId.concat(storeId)).style.background= '#FBBA00';
			}
			else
			{
				document.getElementById(elementId.concat(storeId)).style.background= '#E42528';
			}
			
		} 

		xmlreqs[pos].freed = 1;
	}
}
</script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25647037-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>
<body>


<?php
//Maybe im wrong, seems to be working without it, will leave this here incase it needs to be re-enabled
//It would appear argos does not like people using their pictures! Faking a user agent seems to be the only way of getting it to show up
//ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');

$productId = $_GET["productId"];
$productId = validateProductId($productId);

$infoFileName = "data.csv";

include_once('header.php');

echo '<font face="Arial" size="6">Stock Checker:</font>';
echo "<br />";
echo 'Enter a product ID to check the stock levels throughout the country.';
echo "<br />";
echo "<br />";

echo '	<form name="input" action="'.$_SERVER['PHP_SELF'].'" method="get">
			Product ID: <input type="text" name="productId" value="'.$productId.'" />
			<input type="submit" value="Submit" />
		</form>';
		
echo "<br />";

if ($productId == "")
{
	echo "Please enter a product ID";
	echo "<br />";
	require_once("footer.php");
	echo'</center>';
}
else
{


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

	$stores = array(
					"Arklow (Extra)" => 4101,
					"Ashbourne Retail Park" => 943,
					"Athlone" => 262,
					"Blanchardstown Shopping Centre" => 398,
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
	
	//$checkNI = $_GET["checkNI"];
	$checkNI = "false";
	
	if ($checkNI == "true")
	{
		echo "<br />";
		echo "<br />";
	
		echo '<b>Northern Irish Stores:</b>';
	
		echo "<br />";
		
		$productPage = "http://www.argos.co.uk/static/Product/partNumber/".$productId.".htm";
		//for the moment lets just re-use the product name from argos.ie
		echo '<a href="'.$productPage.'">'.$productTitle[0].'at Argos.co.uk</a>';
		echo "<br /><br />";
		
		echo '
			<table border="1">
				<tr>
					<td><b>Store Name</b></td>
					<td><b>Stock Status</b></td>
					<td>&nbsp;&nbsp;&nbsp;</td>
					<td><b>Store Name</b></td>
					<td><b>Stock Status</b></td>
				</tr>';
					
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
	
	
require_once("footer.php");

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
</body>
</html>
