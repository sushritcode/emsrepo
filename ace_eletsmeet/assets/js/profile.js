function sendData(frmName)
{

	var uri = getAllElementsValURI(frmName);
	var frmAction =BASEURL+"users/save/";
		xmlhttp = initAjax();
		xmlhttp.onreadystatechange = function() 
		{ 
			if(xmlhttp.readyState==4)
			{
				
			} 
		};
	var url = frmAction+uri;
	xmlhttp.open("POST",url,true);
	xmlhttp.send(null);
	return false;
};
