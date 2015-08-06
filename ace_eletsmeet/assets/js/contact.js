function sendData(frmName,type)
{
	document.getElementById("alert").style.display = "none";
	document.getElementById("succ").style.display = "none";
	document.getElementById("successmsg").innerHTML="";
	document.getElementById("err").style.display = "none";
	document.getElementById("errormsg").innerHTML="";
	var uri = getAllElementsValURI(document.forms[frmName]);
	document.getElementById("ajax_loader").style.display = "";
	var frmAction =BASEURL+"contact/api/action.php?action="+type+"&";
	xmlhttp = initAjax();
	xmlhttp.onreadystatechange = function() 
	{ 
		if(xmlhttp.readyState==4)
		{

			if(xmlhttp.responseText == 1 )
				showAlert(1,"You updated the contact information !!!");
			else
				showAlert(0,"Please try agPlease try again , there was some error.");
			document.getElementById("ajax_loader").style.display = "none";
		} 
	};
	var url = frmAction+uri;
	console.debug(url);
	//xmlhttp.open("POST",url,true);
	//xmlhttp.send(null);
	return false;
};
function showAlert(type,message)
{	

	document.getElementById("alert").style.display = "none";
	document.getElementById("succ").style.display = "none";
	document.getElementById("successmsg").innerHTML="";
	document.getElementById("err").style.display = "none";
	document.getElementById("errormsg").innerHTML="";

	document.getElementById("alert").style.display = "";
	if(type == 1)
	{
		document.getElementById("succ").style.display = "";
		document.getElementById("successmsg").innerHTML = message;
	}
	else
	{
		document.getElementById("err").style.display = "";
		document.getElementById("errormsg").innerHTML = message;
	}
	return true;
}
