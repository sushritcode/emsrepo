<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . 'client_authfunc.inc.php');
$CONST_MODULE = 'error';
$CONST_PAGEID = 'Error Home';
require_once(INCLUDES_PATH . 'client_authorize.inc.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
            <script type="text/javascript">
	 	var BASEURL = "<?php echo $SITE_ROOT;?>"; 
	</script>
        <!-- HEAD CONTENT AREA -->
        <?php include (INCLUDES_PATH . 'head.php'); ?>
        <!-- HEAD CONTENT AREA -->

        <!-- CSS n JS CONTENT AREA -->
        <?php include (INCLUDES_PATH . 'css_include.php'); ?>    
        <!-- CSS n JS CONTENT AREA -->
    </head>
    
    <body class="no-skin">

       <!-- TOP NAVIGATION BAR START -->
        <div id="navbar" class="navbar navbar-default">
             <?php include (INCLUDES_PATH . 'top_navigation.php'); ?>    
        </div>
        <!-- TOP NAVIGATION BAR END -->

        <!-- MAIN CONTAINER START -->
        <div class="main-container" id="main-container">
            <script type="text/javascript">
                try {
                    ace.settings.check('main-container', 'fixed')
                } catch (e) {
                }
            </script>

            <!-- MAIN CONTENT START -->
            <div class="main-content">
                <div class="main-content-inner">

                    <!--  PAGE CONTENT START -->
                    <div class="page-content">

                        <!-- PAGE HEADER -->
                        <div class="page-header">
                            <h1>
                                Error !
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
                             
                                
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="center">
                                            <img src="<?php echo IMG_PATH; ?>oops_image.png">
                                        </div>
                                        <div class="center">
                                            <h4>LetsMeet failed due to any of these reasons:</h4>
                                            <h5>Invalid meeting request</h5>
                                            <h5>Our system encountered an obstacle</h5>
                                            <h5>Still unable to join meeting? write to us at support@letsmeet.com</h5>
                                        </div>
                                    </div>    
                                </div>
                                
                                
                                
                                <!-- PAGE CONTENT END -->
                            </div>
                        </div> 

                    </div>
                    <!-- PAGE CONTENT END -->

                </div>
            </div>
            <!--  MAIN CONTENT END -->

           <!-- FOOTER START -->
            <div class="footer">
               <?php include (INCLUDES_PATH . 'footer.php'); ?>  
            </div>
            <!-- FOOTER END -->

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>

        </div>
        <!-- MAIN CONTAINER END -->

    </body>
</html>