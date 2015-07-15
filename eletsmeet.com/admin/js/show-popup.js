function showPopup(selector, layer)
{
    var left = ($(window).width() - $(selector).width()) / 2 + $(window).scrollLeft() - 22;
    var top = ($(window).height() - $(selector).height()) / 2 + $(window).scrollTop() - 22;
    if (left < 0)
    {
        left = 0;
    }
    if (top < 0)
    {
        top = 0;
    }
    /*$(selector).css({'left' : left + "px"});
    $(selector).css({'top' : top + "px"});*/
    $(layer).show();
    $(selector).show();
}

function hidePopup(selector, layer)
{ 
    $('#error-msg').css({"display":"none"});
    $("#error-msg").html('');
    $(selector).hide();
    $(layer).hide();
    //window.location.href = "index.php";
}