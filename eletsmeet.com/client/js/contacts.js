function SelectOption()
{
    strGroupName = document.Contact.cmbGroupNameList.value;
    if (document.Contact.cmbGroupNameList.value == '---')
    {
        document.Contact.txtGroupName.disabled = false;
    }
    else
    {
        document.Contact.txtGroupName.disabled = true;
        document.Contact.txtGroupName.value = '';
    }
}

function DeleteNow(strContactId, strContactName)
{
    if (confirm("Are you sure you want to delete selected contact \'" + strContactName + "\' ?"))
    {
        document.frmContactList.txtContactId.value = strContactId;
        document.frmContactList.txtContactName.value = strContactName;
        document.frmContactList.action = 'deletecontact.php';
        document.frmContactList.submit();
    }
}

function EditNow(strContactId, strContactName)
{
    document.forms["frmEditContact_" + strContactId].submit();
    return false;
}