<?php
   // Edit upload location here
   $destination_path = "/tmp/";

   $result = 0;
   
   $target_path = $destination_path . basename( $_FILES['myfile']['name']);
   $contents  = addslashes(json_encode(file($target_path)));


   if(@move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
      $result = 1;
   }
   
   sleep(1);
?>
<script language="javascript" type="text/javascript">window.top.window.stopUpload(<?php echo $result; ?> , "<?php echo $target_path;?>", "<?php echo $contents;?>");</script>   
