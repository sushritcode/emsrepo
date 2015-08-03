/* ** Show Timezone List ** */
function showTimezone()
{
    if (document.getElementById("timezoneShow").style.display == "block") 
    {
        document.getElementById("timezoneShow").style.display = "none";
    } 
    else 
    {
        document.getElementById("timezoneShow").style.display = "block";
    }
}

function currentZone()
{
    var tzone = $("#timezone").val();
    var sep = "$:$";
    tzone = tzone.split(sep);
    timeZone = tzone[0] + " - " + tzone[1] + ", GMT" + tzone[2];
    $("#curZone").html(timeZone);
    $("#timezoneTitle").html("Meeting Timezone : ");
    showTimezone();
}