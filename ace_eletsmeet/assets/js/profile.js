function sendData(frmName,type)
{

	var uri = getAllElementsValURI(document.forms[frmName]);
	var frmAction =BASEURL+"profile/api/action.php?action=reset&";
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
