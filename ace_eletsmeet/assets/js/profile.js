function sendData(frmName,type)
{
	document.getElementById("alert").style.display = "none";
	document.getElementById("succ").style.display = "none";
	document.getElementById("successmsg").innerHTML="";
	document.getElementById("err").style.display = "none";
	document.getElementById("errormsg").innerHTML="";
	var uri = getAllElementsValURI(document.forms[frmName]);
	document.getElementById("ajax_loader").style.display = "";
	var frmAction =BASEURL+"profile/api/action.php?action="+type+"&";
	xmlhttp = initAjax();
	xmlhttp.onreadystatechange = function() 
	{ 
		if(xmlhttp.readyState==4)
		{

			if(xmlhttp.responseText == 1 )
				showAlert(1,"Your profile has been updated.");
			else if(xmlhttp.responseText == 101 )
				showAlert(0,"Current Password Wrong, Try Again.");
			else
				showAlert(0,"Please try again , there was some error.");
			document.getElementById("ajax_loader").style.display = "none";
		} 
	};
	var url = frmAction+uri;
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

function validatePassword() 
{
	var password =  $("#currentpwd").val();
	var newpwd = $("#newpwd").val();
	var cnfnewpwd = $("#cnfnewpwd").val();
	if(password == "" || newpwd == "" || cnfnewpwd=="")
	{
		showAlert(0,"Please Enter current password , New Password and Confirm new Password !!!");
	}
	else if(newpwd != cnfnewpwd) 
	{
		showAlert(0,"New Passowrd and Confirm Password do not match !!!");
	}
	else
	{
		sendData('frmpassword','resetpwd');
	}
	return false;
	
}
