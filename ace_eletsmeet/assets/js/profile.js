function sendData(frmName,type)
{

	if(!validateElements(document.forms[frmName]))
		return false;

	var uri = getAllElementsValURI(document.forms[frmName]);
	document.getElementById("ajax_loader").style.display = "";
	var frmAction =BASEURL+"profile/api/action.php?action=reset&";
		xmlhttp = initAjax();
		xmlhttp.onreadystatechange = function() 
		{ 
			if(xmlhttp.readyState==4)
			{
				
				if(xmlhttp.responseText == 1 )
					showAlert(1,"You updated your profile");
				else
					showAlert(0,"Please try agPlease try again , there was some error.");
				document.getElementById("ajax_loader").style.display = "none";
			} 
		};
	var url = frmAction+uri;
	xmlhttp.open("POST",url,true);
	xmlhttp.send(null);
	return false;
};

