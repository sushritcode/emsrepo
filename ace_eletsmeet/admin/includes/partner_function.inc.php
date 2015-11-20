<?php

/* -----------------------------------------------------------------------------
  Function Name : isPartnerEmailExists
  Purpose       : To check whether partner email address exists in partner_details table..
  Parameters    : email_address, Datahelper
  Returns       : array (with STATUS)
  Calls         : datahelper.fetchRecords
  Called By     : addpartner.php(Partner)
  ------------------------------------------------------------------------------ */

function isPartnerEmailExists($email_address, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("partner_function.inc.php : isPartnerEmailExists : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("partner_function.inc.php : isPartnerEmailExists : Missing Parameter email_address.", 141);
    }

    try
    {
        $dataHelper->setParam("'" . $email_address . "'", "I");
        $dataHelper->setParam("STATUS", "O");
        $arrIsEmailExists = $dataHelper->fetchRecords("SP", 'IsPartnerEmailExists');
        $dataHelper->clearParams();
        return $arrIsEmailExists;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_function.inc.php : isPartnerEmailExists : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getPartnerId
  Purpose       : To get partner_id for adding new partner in partner_details table.
  Parameters    : Datahelper
  Returns       : MAX(partner_id)
  Calls         : datahelper.fetchRecords
  Called By     : addpartner.php(Partner)
  ------------------------------------------------------------------------------ */

function getPartnerId($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("partner_function.inc.php : getPartnerId : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT MAX(partner_id) FROM partner_details";
        $arrMaxId = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $s1 = $arrMaxId[0]['MAX(partner_id)'];
        $s2 = explode("pr", $s1);
        $s3 = $s2[1] + 1;
        $s4 = strlen($s3);
        switch ($s4)
        {
            case 1: $partnerId = "pr0000" . $s3;
                break;
            case 2: $partnerId = "pr000" . $s3;
                break;
            case 3: $partnerId = "pr00" . $s3;
                break;
            case 4: $partnerId = "pr0" . $s3;
                break;
            case 5: $partnerId = "pr" . $s3;
                break;
            default: break;
        }
    }
    catch (Exception $e)
    {
        throw new Exception("partner_function.inc.php : Get partner id Failed : " . $e->getMessage(), 1111);
    }

    return $partnerId;
}

/* -----------------------------------------------------------------------------
  Function Name : InsertPartnerDetails
  Purpose       : To add client details into client_details table.
  Parameters    : client_id, client_name, admin_id, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     : addclient.php(Client)
  ------------------------------------------------------------------------------ */

function InsertPartnerDetails($partner_id, $partner_name, $email_address, $pwd, $gm_date, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("partner_function.inc.php : InsertPartnerDetails : DataHelper Object did not instantiate", 104);
    }
    if (strlen(trim($partner_id)) <= 0)
    {
        throw new Exception("partner_function.inc.php : InsertPartnerDetails : Missing Parameter partner_id.", 142);
    }
    if (strlen(trim($partner_name)) <= 0)
    {
        throw new Exception("partner_function.inc.php : InsertPartnerDetails : Missing Parameter partner_name.", 142);
    }
    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("partner_function.inc.php : InsertPartnerDetails : Missing Parameter email_address.", 142);
    }
    if (strlen(trim($pwd)) <= 0)
    {
        throw new Exception("partner_function.inc.php : InsertPartnerDetails : Missing Parameter password.", 142);
    }
    try
    {
        $strSqlQuery = "INSERT INTO partner_details(partner_id, partner_name, email_address, password, partner_creation_dtm, status) VALUES('" . trim($partner_id) . "', '" . trim($partner_name) . "', '" . trim($email_address) . "', '" . trim($pwd) . "', '" . trim($gm_date) . "','1')";
        $arrResult = $dataHelper->putRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_function.inc.php : Error in InsertPartnerDetails." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : IsPartnerNameExists
  Purpose       : To check whether partner name exists in partner_details table.
  Parameters    : partner_name, Datahelper
  Returns       : partner_name
  Calls         : datahelper.fetchRecords
  Called By     : addpartner.php(Partner)
  ------------------------------------------------------------------------------ */

function IsPartnerNameExists($partner_name, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("partner_function.inc.php : IsPartnerNameExists : DataHelper Object did not instantiate", 104);
    }
    if (strlen(trim($partner_name)) <= 0)
    {
        throw new Exception("partner_function.inc.php : IsPartnerNameExists : Missing Parameter partner_name.", 143);
    }
    try
    {
        $strSqlQuery = "SELECT COUNT(partner_name) FROM partner_details WHERE partner_name = '" . trim($partner_name) . "' AND status = '1'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_function.inc.php : Error in IsPartnerNameExists." . $e->getMessage(), 734);
    }
}

?>
