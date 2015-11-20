function SetIddCode(iddcode)
{
	iddcode = iddcode.split("|");
	if(document.Contact.CountryName.value == '---')
	{
		document.Contact.txtIddCode.value = '';
	}
	else
	{
		document.Contact.txtIddCode.value = "+"+iddcode[0];
		if(document.Contact.txtTimeZone)
			document.Contact.txtTimeZone.value = iddcode[2]+" "+iddcode[3];
	}
}

function addOption(countrycode)
{
        countrycode = countrycode.split("|"); 
        countrycode = countrycode[2];
	var httpxml;
	try {
		httpxml=new XMLHttpRequest();
	} catch (e) {
		try {
			httpxml=new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				httpxml=new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				alert("Your browser does not support AJAX!");
				return false;
			}
		}
	}

	function stateck() {
		if(httpxml.readyState==4) {
			var myarray = httpxml.responseText;
			if(myarray != "")   {
				arr = myarray.split(",");
				for(j=document.getElementById('TimeZone').options.length-1;j>=0;j--) {
					document.getElementById('TimeZone').remove(j);
				}
				var optn1 = document.createElement("OPTION");
				optn1.text = "Select TimeZone";
				optn1.value = "---";
				document.getElementById('TimeZone').options.add(optn1);
                               	for (i=0;i<arr.length;i++) {
				        timezone = arr[i]; 
					var optn = document.createElement("OPTION");
					optn.text = timezone;
					optn.value = timezone;
					document.getElementById('TimeZone').options.add(optn);
				}
			} else {
				for(j=document.getElementById('TimeZone').options.length-1;j>=0;j--) {
					document.getElementById('TimeZone').remove(j);
				}
				var optn1 = document.createElement("OPTION");
				optn1.text = "Timezone list not available";
				optn1.value = "";
				document.getElementById('TimeZone').options.add(optn1);
				return false;
			}
		}
	}
  	var url = '../includes/getTimezones.php';
	url=url+"?cCode="+countrycode;
	httpxml.onreadystatechange=stateck;
	httpxml.open("GET",url,true);
	httpxml.send(null);
}

function addGroupOption(clientId)
{
        clientId = clientId.split("_");
        clientId = clientId[0];
	var httpxml;
	try {
		httpxml=new XMLHttpRequest();
	} catch (e) {
		try {
			httpxml=new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				httpxml=new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				alert("Your browser does not support AJAX!");
				return false;
			}
		}
	}

	function stateck() {
		if(httpxml.readyState==4) {
			var myarray = httpxml.responseText;
			if(myarray != "")   {
				arr = myarray.split(",");
				for(j=document.getElementById('GroupList').options.length-1;j>=0;j--) {
					document.getElementById('GroupList').remove(j);
				}
				var optn1 = document.createElement("OPTION");
				optn1.text = "Select Group Name";
				optn1.value = "---";
				document.getElementById('GroupList').options.add(optn1);
                               	for (i=0;i<arr.length;i++) {
				        groupname = arr[i]; 
					var optn = document.createElement("OPTION");
					optn.text = groupname;
					optn.value = groupname;
					document.getElementById('GroupList').options.add(optn);
				}
			} else {
				for(j=document.getElementById('GroupList').options.length-1;j>=0;j--) {
					document.getElementById('GroupList').remove(j);
				}
				var optn1 = document.createElement("OPTION");
				optn1.text = "Group Name List not available";
				optn1.value = "";
				document.getElementById('GroupList').options.add(optn1);
				return false;
			}
		}
	}
        var url = '../includes/getClientGroup.php';
	url=url+"?txtClientId="+clientId;
	httpxml.onreadystatechange=stateck;
	httpxml.open("GET",url,true);
	httpxml.send(null);
}