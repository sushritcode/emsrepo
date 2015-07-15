function editUserStatus(strUserId, strFirstName, strLastName, strEmailId, strAction)
{
    document.frmUserList.txtAction.value = "";
    document.frmUserList.txtUserId.value = strUserId;
    document.frmUserList.txtFirstName.value = strFirstName;
    document.frmUserList.txtLastName.value = strLastName;
    document.frmUserList.txtEmailId.value = strEmailId;
    
    if(strAction == 'disable')
    {
        if(confirm("Are you sure you want to disable selected user \'"+ strFirstName + ' ' + strLastName +"\' ?"))
        {
            document.frmUserList.txtAction.value = strAction;
            document.frmUserList.action = 'edituserstatus.php';
        }
    }
    else
    {
        if(confirm("Are you sure you want to enable selected user \'"+ strFirstName + ' ' + strLastName +"\' ?"))
        {
            document.frmUserList.txtAction.value = strAction;
            document.frmUserList.action = 'edituserstatus.php';
        }
    }
    document.frmUserList.submit();
}