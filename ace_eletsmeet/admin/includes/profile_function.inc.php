<?php

/* -----------------------------------------------------------------------------
  Function Name : updateAdminPassword
  Purpose       : To update password of admin while profile update.
  Parameters    : email_address, old_password, new_password, Datahelper
  Returns       : array (with status, admin_id, email)
  Calls         : datahelper.putRecords
  Called By     :
  Author        : 
  Created  on   : 20-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updateAdminPassword($email_address, $old_password, $new_password, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("profile_authfunc.inc.php : updateAdminPassword : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: updateAdminPassword : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($old_password)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: updateAdminPassword : Missing Parameter old_password.", 142);
    }

    if (strlen(trim($new_password)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: updateAdminPassword : Missing Parameter new_password.", 143);
    }

    try
    {
        if (!is_object($dataHelper))
        {
            throw new Exception("profile_authfunc.inc.php : updateAdminPassword : DataHelper Object did not instantiate", 104);
        }
        $dataHelper->setParam("'".$email_address."'", "I");
        $dataHelper->setParam("'".$old_password."'", "I");
        $dataHelper->setParam("'".$new_password."'", "I");
        $dataHelper->setParam("STATUS", "O");
        $dataHelper->setParam("ADMIN_ID", "O");
        $dataHelper->setParam("EMAIL", "O");
        $arrUpdatePwd = $dataHelper->putRecords("SP", 'UpdateAdminPassword');
        $dataHelper->clearParams();
        return $arrUpdatePwd;
    }
    catch (Exception $e)
    {
        throw new Exception(" profile_authfunc.inc.php : updateAdminPassword : Failed : ".$e->getMessage(), 145);
    }
}