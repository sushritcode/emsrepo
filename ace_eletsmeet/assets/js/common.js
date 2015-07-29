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

