function sendData(frmName,type)
{

	document.getElementById("alert").style.display = "none";
	document.getElementById("succ").style.display = "none";
	document.getElementById("successmsg").innerHTML="";
	document.getElementById("err").style.display = "none";
	document.getElementById("errormsg").innerHTML="";
	var uri = getAllElementsValURI(document.forms[frmName]);
	if(!uri) return false;
	document.getElementById("ajax_loader").style.display = "";
	var frmAction =BASEURL+"contacts/api/action.php?action="+type+"&";
	xmlhttp = initAjax();
	xmlhttp.onreadystatechange = function() 
	{ 
		if(xmlhttp.readyState==4)
		{

			if(xmlhttp.responseText == 1 )
			{
				showAlert(1,"You updated the contact information !!!");
				location.reload();
			}
			else if(xmlhttp.responseText == 2 )
				showAlert(0,"Please fill in all the lfields");
			else if(xmlhttp.responseText == 3 )
				showAlert(0,"Contact already in your list");
			else 
				showAlert(0,"Unexpected situation !!!");
			document.getElementById("ajax_loader").style.display = "none";
			document.getElementById('type').value="";
		} 
	};
	var url = frmAction+uri;
	//console.debug(url);
	xmlhttp.open("POST",url,true);
	xmlhttp.send(null);
	return false;
};
function fetchcontactdetails(userid,contactid,type)
{

	var frmAction =BASEURL+"contacts/api/action.php?action="+type+"&";
	xmlhttp = initAjax();

	document.getElementById("ajax_loader").style.display = "";
	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.responseText == 0 )
				showAlert(0,"No such contact !!!");
			else
			{
				document.getElementById("contactid").value = contactid;
				var jsonRes = xmlhttp.responseText;
				var contactsDetails  = JSON.parse(jsonRes);
				for(key in contactsDetails)
				{
					if(document.getElementById(key))
					{
						switch(document.getElementById(key).type)
						{
							case "text":	
								document.getElementById(key).value = contactsDetails[key];
								break;
							case "select":
							case "select-one":
								for (i = 0; i< document.getElementById(key).options.length; i++)
								{
									if (document.getElementById(key).options[i].value == contactsDetails[key])
									{
										document.getElementById(key).options[i].selected = true;
										break;
									}
								}
								break;
						} 
					}
				}
			}
			document.getElementById("ajax_loader").style.display = "none";
		}
	};
	var url = frmAction+"userid="+userid+"&contactid="+contactid;
	xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
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
