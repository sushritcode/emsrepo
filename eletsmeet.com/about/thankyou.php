<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
$CONST_MODULE = 'auth';
$CONST_PAGEID = 'Thank You';
require_once(INCLUDES_PATH.'cm_authorize.inc.php');
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head content Area -->
    <head>
        <?php include (INCLUDES_PATH.'head.php'); ?>
    </head>
    <!-- Head content Area -->

    <body>

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (INCLUDES_PATH.'navigation.php'); ?>    
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <!-- Main content Area -->
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->

            <!-- Top content Area -->
            <div class="row">
                <div class="span12 hidden-phone" style="position:relative">

                    <!-- Banner Images -->
                    <div id="myCarousel" class="carousel slide">
                        <div class="carousel-inner">
                            <div class="item">
                                <img src="<?php echo IMG_PATH; ?>slide1.jpg" alt="">
                            </div>
                            <div class="item">
                                <img src="<?php echo IMG_PATH; ?>slide2.jpg" alt="">
                            </div>
                            <div class="item active">
                                <img src="<?php echo IMG_PATH; ?>slide3.jpg" alt="">
                            </div>
                        </div>
                        <a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>
                        <a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>
                    </div>
                    <!-- Banner Images -->

                    <!-- Login Box -->

                    <!-- Login Box -->

                    <!-- Forgot Password -->

                    <!-- Forgot Password -->

                </div>
            </div>
            <!-- Top content Area -->

            <!-- Middle content Area -->
            <div class="row landingSlogan">
                <div class="span12">
                    <br>
                    <h2>Thank you for using Q.CONFERENCE.</h2>
                    <br>
                    <h4><a href="<?php echo $SITE_ROOT."features/"; ?>">Click here</a> to know about Q.CONFERENCE.</h4>
                    <br>
                </div>
            </div>
            <!-- Middle content Area -->

            <!-- Bottom content Area -->

            <!-- Bottom content Area -->
        </div>
        <!-- Main content Area -->

        <!-- Footer content Area -->
        <?php include (INCLUDES_PATH.'footer.php'); ?>
        <!-- Footer content Area -->

        <!-- java script  -->
        <?php include (INCLUDES_PATH.'jsinclude.php'); ?>
        <!-- java script  -->



    </body>
</html>