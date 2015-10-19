<?php
if (strlen(trim($strCk_email_address)) > 0) 
{
     $HOME_URL  = $CLIENT_SITE_ROOT;
     $Client_Logo = $strSetClient_Logo;
     $style= 'border-bottom: 1px solid #ffffff;';
}
?>
<div class="navbar  animated fadeIn" style="<?php echo $style; ?>">
    <div class="navbar-inner">
        <div class="container">
            <div class="row">
                <div class="span12" style="margin-bottom: 0px;">
                    <div class="fL">
                        <?php if ( (strlen(trim($strCk_email_address)) > 0) && (strlen(trim($Client_Logo)) > 0) && (trim($strSetClient_Logo_Flag) == '1') ) { ?>
                            <a class="brand" href="<?php echo $HOME_URL; ?>"><img src="<?php echo CLIENT_LOGO_PATH.$Client_Logo; ?>"  width="125" height="75"  vspace="0px" alt="<?php echo $strSetClient_Name; ?>" title="<?php echo $strSetClient_Name; ?>"></a>
                        <?php }else{ ?>                        
                            <a class="brand" href="<?php echo $HOME_URL; ?>"><img src="<?php echo IMG_PATH . CUSTOM_LOGO_NAME; ?>" width="125" height="75" vspace="0px" alt="<?php echo CUSTOM_LOGO_TITLE; ?>" title="<?php echo CUSTOM_LOGO_TITLE; ?>"></a>
                        <?php } ?>
                    </div>
                    
                    <?php if ($CLIENT_CONST_MODULE != "clreset") {?>
                    <div class="fR">
                          <?php include (CLIENT_INCLUDES_PATH.'user_setting.php'); ?>
                    </div>
                    <?php } ?>
                </div>
               
                <?php if ( (strlen(trim($strCk_email_address)) > 0) &&  ($CLIENT_CONST_MODULE != "clreset") ){  ?>
                <div class="span12" style="border: 0px solid red;  line-height: 24px; padding-top: 10px;">
                    <div class="nav-collapse in">
                        <ul class="nav" style="margin: 0px 0px 0px 0px;">
                            <?php
                            echo"<li style='padding-right:5px;'>";
                            if ($CLIENT_CONST_MODULE == "clsubscription")
                            {
                                echo "<a href='".$CLIENT_SITE_ROOT."subscription/' class='active'><i class='icon-white icon-pencil'></i>&nbsp;Subscription</a>";
                            }
                            else
                            {
                                echo "<a href='".$CLIENT_SITE_ROOT."subscription/' class=''><i class='icon-pencil'></i>&nbsp;Subscription</a>";
                            }
                            echo"</li>"; ?>
                            
                            <?php
                            echo"<li style='padding-right:5px;'>";
                            if ($CLIENT_CONST_MODULE == "cluser")
                            {
                                echo "<a href='".$CLIENT_SITE_ROOT."user/' class='active'><i class='icon-white icon-user'></i>&nbsp;Users </a>";
                            }
                            else
                            {
                                echo "<a href='".$CLIENT_SITE_ROOT."user/' class=''><i class='icon-user'></i>&nbsp;Users</a>";
                            }
                            echo"</li>"; ?>
                            
                             <?php
                            echo"<li style='padding-right:5px;'>";
                            if ($CLIENT_CONST_MODULE == "clcontact")
                            {
                                echo "<a href='".$CLIENT_SITE_ROOT."contacts/' class='active'><i class='icon-white icon-book'></i>&nbsp;Contacts</a>";
                            }
                            else
                            {
                                echo "<a href='".$CLIENT_SITE_ROOT."contacts/' class=''><i class='icon-book'></i>&nbsp;Contacts</a>";
                            }
                            echo"</li>"; ?>
                           
                            <?php
                            echo"<li style='padding-right:0px;'>";
                            if ($CLIENT_CONST_MODULE == "clreports")
                            {
                                echo "<a href='".$CLIENT_SITE_ROOT."reports/' class='active'><i class='icon-white icon-file'></i>&nbsp;Reports</a>";
                            }
                            else
                            {
                                echo "<a href='".$CLIENT_SITE_ROOT."reports/' class=''><i class='icon-file'></i>&nbsp;Reports</a>";
                            }
                            echo"</li>"; ?>
                        </ul>
                     </div>
                </div>
                <?php }  else {?>
                <div class="span12" style="border: 0px solid red; line-height: 24px;">&nbsp;</div>
                <?php }  ?>
            </div>
        </div>
    </div>
</div>
 
