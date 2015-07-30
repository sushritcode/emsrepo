function initAjax()
{
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlhttp;
};

function getAllElementsValURI(frmName)
{	

	var uri = "formname="+frmName.name+"&";
	var len  = document.forms[frmName.name].getElementsByTagName("input").length;
	var eleArrray = document.forms[frmName.name].getElementsByTagName("input");
	for(var i=0;i<len;i++)
		uri+=eleArrray[i].name+"="+eleArrray[i].value+"&";
	var len  = document.forms[frmName.name].getElementsByTagName("textarea").length;
	var eleArrray = document.forms[frmName.name].getElementsByTagName("textarea");
	for(var i=0;i<len;i++)
		uri+=eleArrray[i].name+"="+eleArrray[i].value+"&";
	var len  = document.forms[frmName.name].getElementsByTagName("select").length;
	var eleArrray = document.forms[frmName.name].getElementsByTagName("select");
	for(var i=0;i<len;i++)
		uri+=eleArrray[i].name+"="+eleArrray[i].value+"&";

	return uri;
};

function validateElements(frmName)
{	

	var result , len  = document.forms[frmName.name].getElementsByTagName("input").length;
	var eleArrray = document.forms[frmName.name].getElementsByTagName("input");
	for(var i=0;i<len;i++)
	{
		if(Validate.isset(eleArrray[i].attributes["validate"]))
		{

			if(returnValidateVal(eleArrray[i].attributes["validate"].value , eleArrray[i].value))
			{
				showAlert(0,eleArrray[i].attributes["msg"].value);
				return false;
			}
		}
	}
	var len  = document.forms[frmName.name].getElementsByTagName("textarea").length;
	var eleArrray = document.forms[frmName.name].getElementsByTagName("textarea");
	for(var i=0;i<len;i++)
		uri+=eleArrray[i].name+"="+eleArrray[i].value+"&";
	var len  = document.forms[frmName.name].getElementsByTagName("select").length;
	var eleArrray = document.forms[frmName.name].getElementsByTagName("select");
	for(var i=0;i<len;i++)
		uri+=eleArrray[i].name+"="+eleArrray[i].value+"&";

	return uri;
};

function showAlert(type,message)
{
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
