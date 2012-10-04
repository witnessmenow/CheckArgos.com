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
		xmlreqs[pos].xmlhttp.open("GET",'StockCheckBackground.php?NI=false&productId='+productId+'&storeId='+storeId,true);
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
		xmlreqs[pos].xmlhttp.open("GET",'StockCheckBackground.php?NI=true&productId='+productId+'&storeId='+storeId,true);
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