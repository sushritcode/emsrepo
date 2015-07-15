<?php
if (strlen(trim($strCk_email_address)) > 0) 
{
     $HOME_URL  = $SITE_ROOT;
     $Client_Logo = $strSetClient_Logo;
}
?>
<div class="navbar  animated fadeIn">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <?php
            if (strlen(trim($strCk_email_address)) > 0)
            {
                $strReferer = "meeting/";
                $HOME_URL = $SITE_ROOT . $strReferer;
            }
            ?>

            <a class="brand" href="<?php echo $HOME_URL; ?>">
                <?php
                //if (CUSTOM_LOGO_FLAG == 1)
                if (trim($strSetClient_Logo_Flag) == '1')
                {
                ?>
                        <?php if ( (strlen(trim($strCk_email_address)) > 0)  &&   (strlen(trim($Client_Logo)) > 0) ) { ?>
                            <img src="<?php echo $SITE_ROOT."client/images/client_logo/".$Client_Logo; ?>"  width="188" height="105" vspace="3px" alt="<?php echo $strSetClient_Name; ?>" title="<?php echo $strSetClient_Name; ?>">
                        <?php } else {?> 
                            <img src="<?php echo IMG_PATH . CUSTOM_LOGO_NAME; ?>" width="188" height="105" vspace="3px" alt="<?php echo CUSTOM_LOGO_TITLE; ?>" title="<?php echo CUSTOM_LOGO_TITLE; ?>">
                        <?php } ?>
                <?php
                }else{
                ?>
                    <img src="<?php echo IMG_PATH . CUSTOM_LOGO_NAME; ?>" width="188" height="105" vspace="3px" alt="<?php echo CUSTOM_LOGO_TITLE; ?>" title="<?php echo CUSTOM_LOGO_TITLE; ?>">
                <?php } ?>
            </a>


            <div class="fR">

                <?php include (INCLUDES_PATH . 'user_setting.php'); ?>
                
                <div class="cB"></div>

                <div class="nav-collapse in" style="height:auto;">
                    <ul class="nav">
                        <?php
                        if (strlen(trim($strCk_email_address)) > 0)
                        {
                            echo"<li>";
                            if ($CONST_MODULE == "meeting")
                            {
                                echo "<a href='" . $SITE_ROOT . "meeting/' class='active'>My Meetings</a>";
                            }
                            else
                            {
                                echo "<a href='" . $SITE_ROOT . "meeting/' class=''>My Meetings</a>";
                            }
                            echo"</li>
                            <li>";
                            if ($CONST_MODULE == "schedule")
                            {
                                echo "<a href='" . $SITE_ROOT . "schedule/' class='active'>Schedule A Meeting</a>";
                            }
                            else
                            {
                                echo "<a href='" . $SITE_ROOT . "schedule/' class=''>Schedule A Meeting</a>";
                            }
                            echo"</li>
                            <li>";
                            if ($CONST_MODULE == "contact")
                            {
                                echo "<a href='" . $SITE_ROOT . "contacts/' class='active'>My Contacts</a>";
                            }
                            else
                            {
                                echo "<a href='" . $SITE_ROOT . "contacts/' class=''>My Contacts</a>";
                            }
                            echo"</li>";
                        }
                        ?>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
            <div class="cB"></div>
        </div>
    </div>
</div>
