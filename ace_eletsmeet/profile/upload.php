<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
$CONST_MODULE = 'Image';
$CONST_PAGEID = 'Image Upload';
require_once(INCLUDES_PATH.'cm_authorize.inc.php');
require_once(INCLUDES_PATH.'common_function.inc.php');
require_once(INCLUDES_PATH.'profile_function.inc.php');

   // Edit upload location here
   $destination_path = "/tmp/";

   $result = 0;

   $newfilename = "img".md5(time().'image').basename( $_FILES['myfile']['name']);
   
   $target_path = $destination_path . $newfilename;
   //$target_path = $destination_path . basename( $_FILES['myfile']['name']);
   if(@move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) 
   {
      	$result = 1;
	try
	{
   		$contents  = addslashes(file_get_contents($target_path));
	//	$thumbnail = createThumbnail($target_path , "/tmp/thumb/", 32 , 32);
	//	print "here".$thumbnail;
		$res = updateUserImage($strCK_user_id , $contents , $objDataHelper);
		if(!$res)
		 $result = 3;
		 
		
		
	}
	catch(Exception $e)
	{ 
		print_r($e);
		$result =2;
	}

   }
   
   sleep(1);
?>
<script language="javascript" type="text/javascript">alert("helllo");window.top.window.stopUpload(<?php echo $result; ?>);</script>   
