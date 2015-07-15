<?php

Class CError
{

    var $mVarError;
    var $mVarErrArray = array(
        0 => "Success",
        1 => "Could not Connect to Database",
        2 => "SQL Query Failure",
        3 => "Error in Setting Autocommit OFF",
        4 => "Error Starting Transaction",
        5 => "Error in Committing Transaction",
        6 => "Error in Transaction - Rolling Back - SQL Query Failed",
        7 => "Invalid Parameters in Page",
        8 => "Invalid Parameters into Mail Server",
        9 => "No Records in DB",
        10 => "Missing Arguments",
        101 => "Connection String Empty",
        102 => "Invald Connection String",
        103 => "Begin Transaction failed",
        104 => "DataHelper Object did not Instantiate",
        105 => "Could not open Connection",
        107 => "Invalid Parameters in putRecords",
        108 => "putRecords Insert/Update failed",
        109 => "putRecords Stored Procedure not executed",
        110 => "putRecords Problem in getting the OUT Params",
        111 => "putRecords Sql Query / Stored Procedure not defind",
        112 => "Invalid Parameters in fetchRecords",
        113 => "fetchRecords Sql Query failure",
        114 => "fetchRecords Stored Procedure not executed",
        115 => "fetchRecords Problem in getting the OUT Params",
        116 => "fetchRecords Sql Query/Stored Procedure not defind",
        117 => "setParam Parameter Name not defind",
        118 => "setParam Parameter Type not defind",
        119 => "getDataByPage Sql Query not defind",
        120 => "getDataByPage Offset Count not defind",
        121 => "getDataByPage No of rows not defind",
        /* schedule Error Codes Start */
        1101 => "sch_function.inc.php : scheduleDetails : Failed",
        1102 => "sch_function.inc.php : inviteesDetails : Failed",
        1103 => "sch_function.inc.php : getScheduleMeeting : Failed",
        1104 => "sch_function.inc.php : setScheduleCounter : Failed",
        1105 => "sch_function.inc.php : getMeetingListRHS : Failed",
        1106 => "sch_function.inc.php : autoSuggest : Failed",
        1121 => "start.php : validateSchedule : Failed",
        1122 => "start.php : createMeeting : Failed",
        1123 => "start.php : Failed",
        1125 => "index.php : getGroupList : Failed",
        1126 => "index.php : getContactList : Failed",
        1127 => "index.php : getMeetingListRHS : Failed",
        1131 => "createSchedule.php : validateSchedule : Failed",
        1132 => "createSchedule.php : scheduleDetails : Failed",
        1133 => "createSchedule.php : inviteesDetails : Failed",
        1134 => "createSchedule.php : scheduleMail : Failed",
        1135 => "createSchedule.php : Failed",
        1141 => "addInvitee.php : getMeetingInviteeList : Failed",
        1142 => "addInvitee.php : getScheduleMeeting : Failed",
        1143 => "addInvitee.php : inviteesDetails : Failed",
        1144 => "addInvitee.php : setScheduleCounter : Failed",
        1145 => "addInvitee.php : joinMeetingMail : Failed",
        1146 => "addInvitee.php : Failed",
        1151 => "autoSuggest.php : autoSuggest : Failed",
        1152 => "autoSuggest.php : Failed",
        /* schedule Error Codes End */
      
    );
    var $errorFile;
    var $errorMessage;

    function CError()
    {
        $this->mVarError = $ErrCode;
        $this->errorFile = $ErrFile;
        $this->errorMessage = $ErrMess;
    }

    //Let property for ErrorCode
    function setErrorCode($intErrorCode)
    {
        $this->mVarError = $intErrorCode;
    }

    //Set property of Error File
    function SetErrorFile($ErrFile)
    {
        $this->errorFile = $ErrFile;
    }

    //	Get property for ErrorCode
    function getErrorCode()
    {
        return $this->mVarError;
    }

    function GetErrorMsg()
    {
        //echo $this->mVarError;
        return $this->mVarErrArray[$this->mVarError];
    }

    function GetErrorFile()
    {
        return $this->errorFile;
    }

    function GetErrorMsgFromId($msgid)
    {
        //echo $this->mVarError;
        return $this->mVarErrArray[$msgid];
    }

    function Clear()
    {
        $this->mVarError = 0;
    }

    function RaiseError($ErrorFile, $ErrorCode, $strErrMsg, $blnShowError)
    {
        $this->SetErrorFile($ErrorFile);
        $this->setErrorCode($ErrorCode);
        $strMsg = $this->GetErrorMsg();
        $strErrorNo = $this->getErrorCode();

        //$strTrace = "Error Occured in File : ".$ErrorFile."\n | Error No: ".$strErrorNo."\n | Error Message : ".$strErrMsg."\n";
        $strTrace = "Error Occured in File : ".$ErrorFile."<br/> Error No: ".$strErrorNo."<br/> Error Message : ".$strErrMsg."\n";

        //echo "$strTrace".$strTrace;
        if (SHOW_TRACE)
        {
            $strMsg = $strTrace;
            $strMsg = $strMsg."#".$strTrace."#".$blnShowError;
        }
        else
        {
            $strMsg = "<B>Error Message :</B>".$strErrMsg." <BR><B>Error Code :</B>".$ErrorCode."";
            $strMsg = $strMsg."##".$blnShowError;
        }

        $this->ErrorHandler($this->mVarError, $strMsg);
        return NULL;
    }

    function ErrorHandler($errno, $errmsg)
    {
        global $SITE_ROOT;
        $arrErr = split("#", $errmsg);

        $errmsg = $arrErr[0];
        $trace = $arrErr[1];
        $blnDisplay = $arrErr[2];
        if (sizeof($arrErr) > 3)
            $location = $arrErr[3];
        else
            $location = "desktop";

        switch ($errno)
        {
            //case E_USER_ERROR:
            default:
                if (sizeof($_SERVER) > 0)
                {
                    foreach ($_SERVER as $key => $value)
                        $strServerDetails = $strServerDetails."<TR><TD>".$key."</TD><TD>".$value."</TD></TR>";
                }
                if (sizeof($_POST) > 0)
                {
                    foreach ($_POST as $key => $value)
                        $strPostDetails = $strPostDetails."<TR><TD>".$key."</TD><TD>".$value."</TD></TR>";
                }
                if (sizeof($_GET) > 0)
                {
                    foreach ($_GET as $key => $value)
                        $strGetDetails = $strGetDetails."<TR><TD>".$key."</TD><TD>".$value."</TD></TR>";
                }
                if (sizeof($_SESSION) > 0)
                {
                    foreach ($_SESSION as $key => $value)
                        $strSessionDetails = $strSessionDetails."<TR><TD>".$key."</TD><TD>".$value."</TD></TR>";
                }

                $strDetails = "
				  <table BORDER='1'>
					<tr>
					  <td colspan='2' align='center'><b>DETAILS</b></td>
					</tr>
					<tr>
					  <td colspan='2' align='center'><b>SERVER VARIABLES</b></td>
					</tr>".$strServerDetails."
					<tr>
					  <td colspan='2' align='center'><b>POST DATA</b></td>
					</tr>".$strPostDetails."
					<tr>
					  <td colspan='2' align='center'><b>GET DATA</b></td>
					</tr>".$strGetDetails."
					<tr>
					  <td colspan='2' align='center'><b>SESSION DATA</b></td>
					</tr>".$strSessionDetails."
					</table>";
                if (MAIL_ERROR_LOG)
                {
                    error_log(date("l dS of F Y h:i:s A")." ".$trace."<BR>".$strDetails, 1, ERROR_LOG_EMAIL, "Subject: Error reporting - ".$SITE_ROOT."\nFrom:Error Handler\nContent-type: text/html; charset=iso-8859-1\n");
                }
                if (LOG_ERROR)
                {
                    if (ERROR_LOG_FILE_NAME == "")
                    {
                        error_log(date("l dS of F Y h:i:s A")." ".$errmsg.$strDetails, 0);
                    }
                    else
                    {
                        error_log(date("l dS of F Y h:i:s A")." ,".$errmsg."\n", 3, LOGS_PATH.ERROR_LOG_FILE_NAME);
                    }
                }
                $action = $SITE_ROOT."error/";

                if ($blnDisplay)
                {
                    //$errmsg = "There was some technical error encountered. Please try again later";
                    echo"
				      <html>
				      <head></head>
				      <body>
				      <Form Name='frmErrorCode' action='".$action."' method='post'>
					<input type='hidden' name='txtErrorMsg' value='".$errmsg."'>
					<input type='hidden' name='txtErrorNo' value=''>
					<input type='hidden' name='txtModule' value ='".$CONST_MODULE."'>
				      </FORM>
				      <script>document.frmErrorCode.submit();</script>
				      </body>
				      </html>";
                    exit;
                }
        }
    }

}

//Instantiate  CError Class
error_reporting(0);
$ErrorHandler = new CError();
if (!$ErrorHandler)
{
    die("Error Instantiating CError");
    exit;
}
?>
