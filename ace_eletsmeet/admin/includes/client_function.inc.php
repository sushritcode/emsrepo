<?php

/* -----------------------------------------------------------------------------
  Function Name : IsClientNameExists
  Purpose       : To check whether client name exists in client_details table.
  Parameters    : client_name, Datahelper
  Returns       : client_name
  Calls         : datahelper.fetchRecords
  Called By     : addclient.php(Client)
  ------------------------------------------------------------------------------ */

function IsClientNameExists($client_name, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("client_function.inc.php : IsClientNameExists : DataHelper Object did not instantiate", 104);
    }
    if (strlen(trim($client_name)) <= 0)
    {
        throw new Exception("client_function.inc.php : IsClientNameExists : Missing Parameter client_name.", 143);
    }
    try
    {
        $strSqlQuery = "SELECT COUNT(client_name) FROM client_login_details WHERE client_name = '" . trim($client_name) . "' AND client_login_enabled = '1'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_function.inc.php : Error in IsClientNameExists." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getClientId
  Purpose       : To get client_id for adding new client in client_details table.
  Parameters    : Datahelper
  Returns       : MAX(client_id)
  Calls         : datahelper.fetchRecords
  Called By     : addclient.php(Client)
  ------------------------------------------------------------------------------ */

function getClientId($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("client_function.inc.php : getClientId : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT MAX(client_id) FROM client_login_details";
        $arrMaxId = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $s1 = $arrMaxId[0]['MAX(client_id)'];
        $s2 = explode("cl", $s1);
        $s3 = $s2[1] + 1;
        $s4 = strlen($s3);
        switch ($s4)
        {
            case 1: $clientId = "cl0000" . $s3;
                break;
            case 2: $clientId = "cl000" . $s3;
                break;
            case 3: $clientId = "cl00" . $s3;
                break;
            case 4: $clientId = "cl0" . $s3;
                break;
            case 5: $clientId = "cl" . $s3;
                break;
            default: break;
        }
    }
    catch (Exception $e)
    {
        throw new Exception("client_function.inc.php : Get Client Details Failed : " . $e->getMessage(), 1111);
    }

    return $clientId;
}

/* -----------------------------------------------------------------------------
  Function Name : InsertClientDetails
  Purpose       : To add client details into client_details table.
  Parameters    : client_id, client_name, admin_id, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     : addclient.php(Client)
  ------------------------------------------------------------------------------ */

function InsertClientDetails($client_id, $partner_id, $client_name, $client_email , $client_pwd, $gm_date, $logout_url, $rt_server, $rt_server_salt, $rt_server_api, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("client_function.inc.php : InsertClientDetails : DataHelper Object did not instantiate", 104);
    }
    if (strlen(trim($client_id)) <= 0)
    {
        throw new Exception("client_function.inc.php : insUserDetails : Missing Parameter client_id.", 142);
    }
    if (strlen(trim($partner_id)) <= 0)
    {
        throw new Exception("client_function.inc.php : insUserDetails : Missing Parameter partner_id.", 142);
    }
    if (strlen(trim($client_name)) <= 0)
    {
        throw new Exception("client_function.inc.php : insUserDetails : Missing Parameter client_name.", 142);
    }
    if (strlen(trim($client_email)) <= 0)
    {
        throw new Exception("client_function.inc.php : insUserDetails : Missing Parameter client_email.", 142);
    }
    if (strlen(trim($client_pwd)) <= 0)
    {
        throw new Exception("client_function.inc.php : insUserDetails : Missing Parameter client_pwd.", 142);
    }
    if (strlen(trim($gm_date)) <= 0)
    {
        throw new Exception("client_function.inc.php : insUserDetails : Missing Parameter gm_date.", 142);
    }
    try
    {
         //$strSqlQuery = "INSERT INTO client_details(client_id, partner_id, client_name,client_email_address ,client_password, client_creation_dtm, logout_url, rt_server_name, rt_server_salt, rt_server_api_url, status) "
         //." VALUES('" . trim($client_id) . "', '" . trim($partner_id) . "', '" . trim($client_name) . "',  '" . trim($client_email) . "', '" . trim($client_pwd) . "',    '" . trim($gm_date) . "','" . trim($logout_url) . "', '" . trim($rt_server) . "', '" . trim($rt_server_salt) . "', '" . trim($rt_server_api) . "', '1');"; 
         $strSqlQuery = "INSERT INTO client_login_details(client_id, partner_id, client_username, client_password, client_name,client_email_address, client_login_enabled, client_creation_dtm, rt_server_name, rt_server_salt, rt_server_api_url, logout_url) "
         ." VALUES('" . trim($client_id) . "', '" . trim($partner_id) . "', '" . trim($client_email) . "', '" . trim($client_pwd) . "', '" . trim($client_name) . "',  '" . trim($client_email) . "', '1' ,   '" . trim($gm_date) . "', '" . trim($rt_server) . "', '" . trim($rt_server_salt) . "', '" . trim($rt_server_api) . "', '" . trim($logout_url) . "');"; 
         $arrResult = $dataHelper->putRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_function.inc.php : Error in InsertClientDetails." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getClientListbyName
  Purpose       : To get client details by client name from client_details table.
  Parameters    : client_name, Datahelper
  Returns       : array (with client details)
  Calls         : datahelper.fetchRecords
  Called By     : index.php(Client)
  ------------------------------------------------------------------------------ */

function getClientListbyName($partner_name, $client_name, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("client_function.inc.php : getClientList : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT pd.partner_name, cd.client_id, cd.client_name, cd.client_creation_dtm, cd.client_login_enabled FROM client_login_details AS cd, partner_details AS pd WHERE cd.partner_id = pd.partner_id AND pd.partner_name LIKE '%" . trim($partner_name) . "%' AND cd.client_name LIKE '%" . trim($client_name) . "%' AND cd.client_login_enabled = '1' ORDER BY pd.partner_name, cd.client_name";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_function.inc.php : Error in getClientListbyName." . $e->getMessage(), 734);
    }
}
