<?php

/* -----------------------------------------------------------------------------
  Function Name : isContactEmailExists
  Purpose       : To check whether email address already exists while adding contact.
  Parameters    : email_address, owner_id, client_id, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 16-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isContactEmailExists($email_address, $owner_id, $client_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("contact_function.inc.php : isContactEmailExists : DataHelper Object did not instantiate", 104);
    }
    
    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("contact_function.inc.php : isContactEmailExists : Missing Parameter email_address.", 141);
    }
    
    if (strlen(trim($owner_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : isContactEmailExists : Missing Parameter owner_id.", 143);
    }
    
    if (strlen(trim($client_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : isContactEmailExists : Missing Parameter client_id.", 143);
    }
    
    try
    {
      $dataHelper->setParam("'".$email_address."'","I");
      $dataHelper->setParam("'".$owner_id."'","I");
      $dataHelper->setParam("'".$client_id."'","I");
      $dataHelper->setParam("STATUS","O");
      $dataHelper->setParam("FLAG","O");
      $arrIsEmailExists = $dataHelper->fetchRecords("SP",'IsContactEmailExists');
      $dataHelper->clearParams();
      return $arrIsEmailExists;
    }
    catch(Exception $e)
    {
      throw new Exception(" contact_function.inc.php : isContactEmailExists : Failed : ".$e->getMessage(),145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : isContactGroupExists
  Purpose       : To check whether group name already exists while adding a group in contact.
  Parameters    : group_name, owner_id, client_id, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 16-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isContactGroupExists($group_name, $owner_id, $client_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("contact_function.inc.php : isContactGroupExists : DataHelper Object did not instantiate", 104);
    }
    
    if (strlen(trim($group_name)) <= 0)
    {
        throw new Exception("contact_function.inc.php : isContactGroupExists : Missing Parameter group_name.", 141);
    }
    
    if (strlen(trim($owner_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : isContactGroupExists : Missing Parameter owner_id.", 143);
    }
    
    if (strlen(trim($client_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : isContactGroupExists : Missing Parameter client_id.", 143);
    }
    
    try
    {
      $dataHelper->setParam("'".$group_name."'","I");
      $dataHelper->setParam("'".$owner_id."'","I");
      $dataHelper->setParam("'".$client_id."'","I");
      $dataHelper->setParam("STATUS","O");
      $dataHelper->setParam("FLAG","O");
      $arrIsEmailExists = $dataHelper->fetchRecords("SP",'IsContactGroupExists');
      $dataHelper->clearParams();
      return $arrIsEmailExists;
    }
    catch(Exception $e)
    {
      throw new Exception(" contact_function.inc.php : isContactGroupExists : Failed : ".$e->getMessage(),145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insContactDetails
  Purpose       : To insert contact details.
  Parameters    : nick_name, first_name, last_name, email_address, idd_code, mobile, group_name, contact_type, owner_id, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 16-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function insContactDetails($nick_name, $first_name, $last_name, $email_address, $idd_code, $mobile, $group_name, $contact_type, $owner_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("contact_function.inc.php : insContactDetails : DataHelper Object did not instantiate", 104);
    }
    
    if (strlen(trim($nick_name)) <= 0)
    {
        throw new Exception("contact_function.inc.php : insContactDetails : Missing Parameter nick_name.", 142);
    }
    
    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("contact_function.inc.php : insContactDetails : Missing Parameter email_address.", 141);
    }  
    
    if (strlen(trim($group_name)) <= 0)
    {
        throw new Exception("contact_function.inc.php : insContactDetails : Missing Parameter group_name.", 143);
    }
    
    if (strlen(trim($contact_type)) <= 0)
    {
        throw new Exception("contact_function.inc.php : insContactDetails : Missing Parameter contact_type.", 143);
    }
    
    if (strlen(trim($owner_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : insContactDetails : Missing Parameter owner_id.", 143);
    }
    
    try
    {
      $dataHelper->setParam("'".$nick_name."'","I");
      $dataHelper->setParam("'".$first_name."'","I");
      $dataHelper->setParam("'".$last_name."'","I");
      $dataHelper->setParam("'".$email_address."'","I");
      $dataHelper->setParam("'".$idd_code."'","I");
      $dataHelper->setParam("'".$mobile."'","I");
      $dataHelper->setParam("'".$group_name."'","I");
      $dataHelper->setParam("'".$contact_type."'","I");
      $dataHelper->setParam("'".$owner_id."'","I");
      $dataHelper->setParam("STATUS","O");
      $dataHelper->setParam("MESSAGE","O");
      $arrAddDetails = $dataHelper->putRecords("SP",'InsertContactDetails');
      $dataHelper->clearParams();
      return $arrAddDetails;
    }
    catch(Exception $e)
    {
      throw new Exception(" contact_function.inc.php : insContactDetails : Failed : ".$e->getMessage(),145);
    }
     
}

/* -----------------------------------------------------------------------------
  Function Name : getContactListbyType
  Purpose       : To get contact list by using contact type (P or C).
  Parameters    : owner_id, contact_type, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 16-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getContactListbyType($owner_id, $contact_type, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("contact_function.inc.php : getContactListbyType : DataHelper Object did not instantiate", 104);
    }
    
    if (strlen(trim($owner_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : getContactListbyType : Missing Parameter owner_id.", 143);
    }
    
    if (strlen(trim($contact_type)) <= 0)
    {
        throw new Exception("contact_function.inc.php : getContactListbyType : Missing Parameter contact_type.", 143);
    }
    
    try
    {
      $dataHelper->setParam("'".$owner_id."'","I");
      $dataHelper->setParam("'".$contact_type."'","I");
      $arrContactList = $dataHelper->fetchRecords("SP",'GetContactList');
      $dataHelper->clearParams();
      return $arrContactList;
    }
    catch(Exception $e)
    {
      throw new Exception("contact_function.inc.php : getContactList : Failed : ".$e->getMessage(),145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updContactDetails
  Purpose       : To update contact details.
  Parameters    : contact_id, nick_name, first_name, last_name, email_address, idd_code, mobile, group_name, contact_type, owner_id, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 16-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updContactDetails($contact_id, $nick_name, $first_name, $last_name, $email_address, $idd_code, $mobile, $group_name, $contact_type, $owner_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("contact_function.inc.php : updContactDetails : DataHelper Object did not instantiate", 104);
    }
    
    if (strlen(trim($contact_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : updContactDetails : Missing Parameter contact_id.", 142);
    }
    
    if (strlen(trim($nick_name)) <= 0)
    {
        throw new Exception("contact_function.inc.php : updContactDetails : Missing Parameter nick_name.", 142);
    }
    
    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("contact_function.inc.php : updContactDetails : Missing Parameter email_address.", 141);
    }
    
    if (strlen(trim($group_name)) <= 0)
    {
        throw new Exception("contact_function.inc.php : updContactDetails : Missing Parameter group_name.", 143);
    }
    
    if (strlen(trim($contact_type)) <= 0)
    {
        throw new Exception("contact_function.inc.php : updContactDetails : Missing Parameter contact_type.", 143);
    }
    
    if (strlen(trim($owner_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : updContactDetails : Missing Parameter owner_id.", 143);
    }
    
    try
    {
      $dataHelper->setParam("'".$contact_id."'","I");
      $dataHelper->setParam("'".$nick_name."'","I");
      $dataHelper->setParam("'".$first_name."'","I");
      $dataHelper->setParam("'".$last_name."'","I");
      $dataHelper->setParam("'".$email_address."'","I");
      $dataHelper->setParam("'".$idd_code."'","I");
      $dataHelper->setParam("'".$mobile."'","I");
      $dataHelper->setParam("'".$group_name."'","I");
      $dataHelper->setParam("'".$contact_type."'","I");
      $dataHelper->setParam("'".$owner_id."'","I");
      $dataHelper->setParam("STATUS","O");
      $dataHelper->setParam("MESSAGE","O");
      $arrUpdateDetails = $dataHelper->putRecords("SP",'UpdateContactDetails');
      $dataHelper->clearParams();
      return $arrUpdateDetails;
    }
    catch(Exception $e)
    {
      throw new Exception(" contact_function.inc.php : updContactDetails : Failed : ".$e->getMessage(),145);
    }
}


/* -----------------------------------------------------------------------------
  Function Name : getContactGroupList
  Purpose       : To get group names while adding contact.
  Parameters    : owner_id, Datahelper
  Returns       : array (with group names)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 17-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getContactGroupList($owner_id, $dataHelper)
{
    if(!is_object($dataHelper))
    {
	throw new Exception("contact_function.inc.php : ContactGroupList : DataHelper Object did not instantiate",104);
    }
    
    if (strlen(trim($owner_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : ContactGroupList : Missing Parameter owner_id.", 143);
    }
    
    try
    {
       $strSqlQuery = "SELECT DISTINCT contact_group_name FROM personal_contact_details WHERE user_id = '". trim($owner_id)."';";
       $arrResult = $dataHelper->fetchRecords("QR",$strSqlQuery);
       return $arrResult;
    }
    catch(Exception $e)
    {
       throw new Exception("contact_function.inc.php : Error in ContactGroupList.".$e->getMessage(),734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getContactDetails
  Purpose       : To get contact details for updating.
  Parameters    : contact_id, Datahelper
  Returns       : array (with personal_contact_details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 16-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getContactDetails($contact_id, $dataHelper)
{
    if(!is_object($dataHelper))
    {
	throw new Exception("contact_function.inc.php : getContactDetails : DataHelper Object did not instantiate",104);
    }
 
    if (strlen(trim($contact_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : getContactDetails : Missing Parameter contact_id.", 143);
    }
    
    try
    {
       $strSqlQuery = "SELECT * FROM personal_contact_details WHERE personal_contact_id = '". trim($contact_id)."'";
       $arrResult = $dataHelper->fetchRecords("QR",$strSqlQuery);
       return $arrResult;
    }
    catch(Exception $e)
    {
       throw new Exception("contact_function.inc.php : Error in getContactDetails.".$e->getMessage(),734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : delContactDetails
  Purpose       : To delete a contact.
  Parameters    : contact_id, owner_id, contact_type, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 18-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function delContactDetails($contact_id, $owner_id, $contact_type, $dataHelper)
{
   
    if (!is_object($dataHelper))
    {
        throw new Exception("contact_function.inc.php : delContactDetails : DataHelper Object did not instantiate", 104);
    }
    
    if (strlen(trim($contact_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : delContactDetails : Missing Parameter contact_id.", 143);
    }
    
    if (strlen(trim($owner_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : delContactDetails : Missing Parameter owner_id.", 143);
    }
    
    if (strlen(trim($contact_type)) <= 0)
    {
        throw new Exception("contact_function.inc.php : delContactDetails : Missing Parameter contact_type.", 143);
    }
    
    try
    {
      $dataHelper->setParam("'".$contact_id."'","I");
      $dataHelper->setParam("'".$owner_id."'","I");
      $dataHelper->setParam("'".$contact_type."'","I");
      $dataHelper->setParam("STATUS","O");
      $dataHelper->setParam("MESSAGE","O");
      $arrDeleteCnt = $dataHelper->putRecords("SP",'DeleteContactDetails');
      $dataHelper->clearParams();
      return $arrDeleteCnt;
    }
    catch(Exception $e)
    {
       throw new Exception("contact_function.inc.php : delContactDetails : Failed : ".$e->getMessage(),145);
    }
}

?>
