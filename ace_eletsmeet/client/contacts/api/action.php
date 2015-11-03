<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(CLIENT_DBS_PATH . 'DataHelper.php');
require_once(CLIENT_DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_contacts';
$CLIENT_CONST_PAGEID = 'Contacts Action';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_dashboard_function.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_contact_function.inc.php');


if (isset($_REQUEST["action"]))
{
    switch ($_REQUEST["action"])
    {
        case "disable":
            $returnVal = disablecontact($_REQUEST['contactid'], $strSetClient_ID, $objDataHelper);
            ?>
            <script type="text/javascript">window.location.href = "<?php echo $SITE_ROOT . "contacts/"; ?>";</script>
            <?php
            exit;
            break;
        case "enable":
            $returnVal = enablecontact($_REQUEST['contactid'], $strSetClient_ID, $objDataHelper);
            ?>
            <script type="text/javascript">window.location.href = "<?php echo $SITE_ROOT . "contacts/"; ?>";</script>
            <?php
            exit;
            break;
        case "add":
            $formMaps = profile_form_table_map_contacts();
            $_REQUEST["association"] = $strSetClient_ID;
            $_REQUEST["updatedon"] = date("Y-m-d H:i:s");
            $arrContact = getAllcontactsByEmailId($strSetClient_ID, $_REQUEST['contactemailaddress'], $objDataHelper);
            if (count($arrContact) > 0)
            {
                echo "3";
                exit;
            }

            // for new group name and existing group name start
            if (trim($_REQUEST["newcontactgroupname"]) != "")
                $_REQUEST["contactgroup"] = trim($_REQUEST["newcontactgroupname"]);
            // for new group name and existing group name end

            $insertParams = getInsertQueryString($_REQUEST, $formMaps);
            if ($insertParams == -1)
            {
                echo "2";
                exit;
            }

            $result = change_user_profile($insertParams, $objDataHelper, $strSetClient_ID, "add");
            echo "1";


            break;
        case "update":
            $_REQUEST['updatedon'] = date('Y-m-d H:i:s');
            $formMaps = profile_form_table_map_contacts();
            // for new group name and existing group name start
            if (trim($_REQUEST["newcontactgroupname"]) != "")
                $_REQUEST["contactgroup"] = trim($_REQUEST["newcontactgroupname"]);
            // for new group name and existing group name end

            $updateparams = getUpdateQueryString($_REQUEST, $formMaps);
            $result = updateContactProfile($updateparams, $_REQUEST["contactid"], $objDataHelper, $strSetClient_ID, $_REQUEST["action"]);
            echo $result;

            break;
        case "getcontact":
            $arrContact = getContactByContactid($strSetClient_ID, $_REQUEST["contactid"], $objDataHelper);
            $formMaps = profile_form_table_map_contacts();
            $frmName = "frmcontact";
            $arrContactDetails = Array();
            foreach ($formMaps[$frmName] as $key => $value)
                $arrContactDetails[$key] = $arrContact[0][$value];
            if (count($arrContactDetails) == 0)
                echo "0";
            else
                echo json_encode($arrContactDetails);
            break;
        case "uploadfiledata":
            $checkKeys = Array("contact_email_address", "contact_mobile_number");

            $filename = $_REQUEST['filename'];
            $filecontents = file($filename);
            $formname = $_REQUEST["formname"];
            $formMaps = profile_form_table_map_contacts();

            for ($i = 0; $i < count($filecontents); $i++)
            {
                $arrFileLive = explode(",", $filecontents[$i]);

                $index1 = $_REQUEST[$formMaps[$formname]['contact_email_address']];
                $index2 = $_REQUEST[$formMaps[$formname]['contact_mobile_number']];
                $queryString = $columns = "";
                if ($arrFileLive[$index1] != "" && $arrFileLive[$index2] != "")
                {
                    $arrContact = getAllcontactsByEmailId($strSetClient_ID, $arrFileLive[$index1], $objDataHelper);
                    if (count($arrContact) == 0)
                    {
                        foreach ($formMaps[$formname] as $key => $value)
                        {
                            if ($queryString != "")
                                $queryString.=",";
                            if ($columns != "")
                                $columns.=",";
                            $columns.=$key;


                            if ($value == "selgroupname")
                                $queryString.= "'" . $_REQUEST[$value] . "' ";

                            elseif ($value == "selcountry")
                            {
                                $arrCountry = getDistinctCountryByCountryName($arrFileLive[$_REQUEST[$value]], $objDataHelper);
                                if (count($arrCountry) > 0)
                                    $queryString.="'" . $arrCountry[0]['country_idd_code'] . "' ";
                                else
                                    $queryString.="'91'";
                            }
                            else
                                $queryString.="'" . $arrFileLive[$_REQUEST[$value]] . "' ";
                        }
                        $columns.=", user_id";
                        $queryString.=", '" . $strSetClient_ID . "'";
                        $queryString = "Insert into client_contact_details (" . $columns . ") VALUES (" . $queryString . ");";
                        $result = $objDataHelper->putRecords("QR", $queryString);
                    }
                }
            }
            echo "1";
            exit;

            break;
    }
}
?>
