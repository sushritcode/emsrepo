var errorLength = 0;
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

	var errorRes = validateForm(frmName.name);
	if(errorRes)
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
	}
	return false;
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
function findFormGroup(ele)
{
	var flag=true , c=0;
	while(flag)
	{

		if(ele.parentNode.className == "form-group")
			return ele.parentNode;
		ele = ele.parentNode;
		if(c == 5 )
			flag=false;
		c++;
	}
	return false;
};
function validateForm(frmName) 
{
	var errArr = document.forms[frmName].getElementsByTagName("err");
        for(var k=0;k < errArr.length;k++)
                deleteElements(errArr[k]);

	var err = true;
	if(!document.forms[frmName])
		return false;
	var elementArrayType = Array("input","textarea","select");
	for(var j=0;j < elementArrayType.length ; j++)
	{
		var eleArray = document.forms[frmName].getElementsByTagName(elementArrayType[j]);
		var len = eleArray.length;
		for(var i=0;i < len ; i++)
		{
			if(eleArray[i].hasAttribute("validate"))
			{
				var val = eleArray[i].value;
				if (val == null || val == "") 
				{
					var ele = findFormGroup(document.getElementById(eleArray[i].id));
					if(ele)
						ele.className +=" has-error";
					var errorMsg = "";
					if(eleArray[i].hasAttribute("msg"))
						var errorMsg = eleArray[i].attributes["msg"].value;
					var errDiv = makeErrorDiv(errorMsg);
					eleArray[i].parentNode.appendChild(errDiv);
					err=false;
				}
			}
		}
	}
	return err;
};
function __createElement(tag, cls, id, name)
{
        var ele; 
        ele = document.createElement(tag);
        if(cls != "")
                ele.className = cls; 
        if(id != "")
                ele.id = id;
        if(name != "")
                ele.name = name;
        return ele; 
};
function deleteElements(ele)
{
        var p, e, i;
        if(ele)
                e = ele;
        else
                return;

        if(e.childNodes) {
                var len = e.childNodes.length;
                for(i = 0; i < len; i++)
                        deleteElements(e.firstChild);
        }
        p = e.parentNode;
        if(p)
                p.removeChild(e);
        delete e;
};

function makeErrorDiv(msg)
{
	if(msg=='') 
		msg = "This is a required Filed";
	
	var clearEle = __createElement("DIV","clearfix");
	
	var ele = __createElement("DIV" , "help-block");
	ele.innerHTML = msg;
	var mainEle = __createElement("err","","","");
	mainEle.appendChild(clearEle);
	mainEle.appendChild(ele);
	return mainEle;
};







