<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'clcontact';
$CLIENT_CONST_PAGEID = 'Contact CSV';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

try
{ 
    if ( isset($_POST["Submit"]) ) 
    {
        if($_FILES[csv][size] == 0)
        {
            $errors[] = 'Please select your csv file.';
        }
         else
         {
            $allowedExtension = array("csv");
            foreach ($_FILES as $file)
            {
               if ($file['tmp_name'] > '')
               {
                   if (!in_array(end(explode(".",
                   strtolower($file['name']))),
                   $allowedExtension)) {
                       $errors[] = '<b><font color=#006699>"'.$file['name'].'"</font></b> is an invalid file type.';
                   }
               }
            }
        }
        if (sizeof($errors) == 0)
        {   
            if ($_FILES[csv][size] > 0)
            {
                $file = $_FILES[csv][tmp_name];
                $handle = fopen($file,"r");
                //$first = true;

                 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
                {                    
//                    if ($first) 
//                    {
//                             $first = false;
//                             continue;
//                    }
                   
                   if($data[0] == '' || $data[1] == '' || $data[2] == '' || $data[3] == '' || $data[6] == '')
                   {
                       $errors[] = 'Please check your data.';
                   }
                   else
                   {    
                       try
                       {
                              $isEmailExists = isContactEmailExists($data[3], $strSetClient_ID, $objDataHelper);
                               if ($isEmailExists != 0)
                              {
                                 $errors[] = 'Email Address <b><font color=#006699>"'.$data[3].'"</font></b> already exists.';
                              }
                        }
                        catch(Exception $a)
                        {
                             throw new Exception("import.php : isContactEmailExists : Error in checking email address.".$a->getMessage(),541);
                        }
                    }
                    
                    if (sizeof($errors) == 0) 
                    {
                         try
                         {
                             $strSqlStatement = "INSERT INTO client_contact_details (contact_nick_name, contact_first_name, contact_last_name, contact_email_address, contact_idd_code, contact_mobile_number, contact_group_name, client_id, client_contact_status) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]', '$strSetClient_ID','1')";
                             $arrList = $objDataHelper->putRecords("QR", $strSqlStatement);
                         }
                         catch(Exception $a)
                         {
                             throw new Exception("import.php : insert : Error in inserting data.".$a->getMessage(),541);
                         }
                         $success = 'The file <b><font color=#006699>"'.$_FILES[csv][name].'"</font></b> has been uploaded.';
                   }
              }
              //fclose($handle);
           }
       }
   } 
   if (isset($_POST['btnCancel']))
   {
       header("Location:" .$CLIENT_SITE_ROOT.'contacts/');
   }
}
catch (Exception $e)
{
     $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), true);
}
?>
<!DOCTYPE html>
<html lang="en">
  <!-- Head content Area -->
  <head>
    <?php include (CLIENT_INCLUDES_PATH.'head.php'); ?>    
  </head>
  <!-- Head content Area -->
  
  <body>
      
    <!-- Navigation Bar, After Login Menu &  Product Logo -->
    <?php include (CLIENT_INCLUDES_PATH.'navigation.php'); ?>    
    <!-- Navigation Bar, After Login Menu &  Product Logo -->
    
    <!-- Main content Area -->
    <div class="container">
        <!-- Main hero unit for a primary marketing message or call to action -->
      
        <!-- Middle content Area -->
        <div class="row">
            
                        <div class="span12">
                            <div class="fL">
                                <h3>Import CSV</h3>
                            </div>			
                        </div>
                                 
                        <div class="span12"><hr>
			
                            <?php if (count($errors)): ?>
                             <div class="alert alert-error"> 
                               <?php foreach ($errors as $error): ?>
                                  <span><?php echo $error; ?></span><br />    
                               <?php endforeach; ?>
                             </div>
                           <?php endif; ?>
                           <?php if ($success): ?>
                             <div class="alert alert-success"> 
                                 <span><?php echo $success; ?></span><br /> 
                               </div></br>
                           <?php endif; ?>
                               <?php if (empty($success)) { ?>
                               
                               <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" name="csvimport">
                                  Choose your file: <br />
                                  <input name="csv" type="file" name="csv" class="span3"/><br><br>
                                  <input type="submit" name="Submit" class="btn btn-primary" value="Submit" />
                                  <input type="submit" name="btnCancel" class="btn btn-primary mL10" value="Cancel" />
                               </form>
		               <?php } else { ?>
                                 <form name="csvimport" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                      <input type="submit" name="btnCancel" class="btn btn-primary" value="Back" />
                                </form>
                               <?php } ?>
				<hr>
			</div>
	   </div>
        <!-- Middle content Area -->
        </div>
    <!-- Main content Area -->
    
    <!-- Footer content Area -->
    <?php include (CLIENT_INCLUDES_PATH.'footer.php'); ?>
    <!-- Footer content Area -->

    <!-- java script  -->
    <?php include (CLIENT_INCLUDES_PATH.'jsinclude.php'); ?>
    <!-- java script  -->
  
  </body>
</html>
