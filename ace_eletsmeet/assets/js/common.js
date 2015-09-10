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
function forgotPwd(frmName,type)
{
        document.getElementById("alert").style.display = "none";
        document.getElementById("succ").style.display = "none";
        document.getElementById("successmsg").innerHTML="";
        document.getElementById("err").style.display = "none";
        document.getElementById("errormsg").innerHTML="";
        var uri = getAllElementsValURI(document.forms[frmName]);
        document.getElementById("ajax_loader").style.display = ""; 
        var frmAction = BASEURL+"profile/api/action.php?action="+type+"&";
        var url = frmAction+uri;
        var xmlhttp = initAjax();
        xmlhttp.onreadystatechange = function() 
        {
                if(xmlhttp.readyState==4)
                {

                        if(xmlhttp.responseText == 1 ) 
                                showAlert(1,"Please Check your email and follow the steps!!!");
                        else
                                showAlert(0,"Please try again , there was some error.");
                        document.getElementById("ajax_loader").style.display = "none";
                }
        };
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
};





