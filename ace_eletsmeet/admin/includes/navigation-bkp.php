<?php
/*
 * Navigation Bar Code for EMeet
 */
?>
<div class="navbar  animated fadeIn">
    <div class="navbar-inner">
        <div class="container">
            <?php
            if (strlen(trim($strCk_email_address)) > 0) 
            {
                //echo $strAdminClientl_Id;
                //echo $strAdminPartner_Id;
                if ($strAdminFlag <>"ca") 
                {    
                    $strReferer = "user/";
                    $HOME_URL = $ADMIN_SITE_ROOT.$strReferer;
                }
                else
                {
                    $strReferer = "reports/";
                    $HOME_URL = $ADMIN_SITE_ROOT.$strReferer;
                }
            }
            else
            {
                $HOME_URL = $ADMIN_SITE_ROOT;
            }
            ?>
            
<!--            <a class="brand" href="<?php echo $HOME_URL; ?>"><img src="<?php echo ADM_IMG_PATH; ?>quadridge-logo-white.png" vspace="10px" height="105" width="188" alt="Quadridge Technologies" title="Quadridge Technologies"></a>-->
            <a class="brand" href="#"><img src="<?php echo IMG_PATH; ?>letsmeet.png" vspace="0px" alt="LetsMeet" title="LetsMeet"></a>
            
            <div class="fR">
                
                <?php include (ADM_INCLUDES_PATH.'user_setting.php'); ?>
                
            <div class="cB"></div>
            
                <?php //include (ADM_INCLUDES_PATH . 'user_setting.php'); ?>    
            
            <div class="nav-collapse in">
                <ul class="nav" style="border-bottom: 1px solid; margin: 0px 0px 0px 0px;">
                    <?php
                    if (strlen(trim($strCk_email_address)) > 0) 
                    {
                        if ($strAdminFlag <>"ca")
                        {
                            if ($strAdminFlag == "s")
                            {
                                echo"<li>";
                                if ($ADM_CONST_MODULE == "partner")
                                {
                                    echo "<a href='".$ADMIN_SITE_ROOT."partner/' class='active'>Partner List</a>";
                                }
                                else
                                {
                                    echo "<a href='".$ADMIN_SITE_ROOT."partner/' class=''>Partner List</a>";
                                }
                                echo"</li>";
                            }
                            echo"<li>";
                            if ($ADM_CONST_MODULE == "client")
                            {
                                echo "<a href='".$ADMIN_SITE_ROOT."client/' class='active'>Client List</a>";
                            }
                            else
                            {
                                echo "<a href='".$ADMIN_SITE_ROOT."client/' class=''>Client List</a>";
                            }
                            echo"</li>
                            <li>";
                            if ($ADM_CONST_MODULE == "user")
                            {
                                echo "<a href='".$ADMIN_SITE_ROOT."user/' class='active'>User List</a>";
                            }
                            else
                            {
                                echo "<a href='".$ADMIN_SITE_ROOT."user/' class=''>User List</a>";
                            }
                            echo"</li>
                            <li>";
                            if ($ADM_CONST_MODULE == "reports")
                            {
                                echo "<a href='".$ADMIN_SITE_ROOT."reports/' class='active'>Reports</a>";
                            }
                            else
                            {
                                echo "<a href='".$ADMIN_SITE_ROOT."reports/' class=''>Reports</a>";
                            }
                            echo"</li>";
                        }
                        else
                        {
//                            echo"<li>";
//                            if ($ADM_CONST_MODULE == "reports")
//                            {
//                                echo "<a href='".$ADMIN_SITE_ROOT."reports/' class='active'>Reports</a>";
//                            }
//                            else
//                            {
//                                echo "<a href='".$ADMIN_SITE_ROOT."reports/' class=''>Reports</a>";
//                            }
//                            echo"</li>";
                                $ADM_CONST_MODULE == "reports";
                        }
                    }
                    ?>
                </ul>
                <?php
                  if ($ADM_CONST_MODULE == "reports")
                  { ?>
                    <ul class="nav" style="margin: 0px 0px 0px 0px;">
                        <li>
                           <?php
                            if ($ADM_CONST_PAGEID == "license_count")
                            {
                                echo "<a href='".$ADMIN_SITE_ROOT."reports/rpt_license_count.php' class='active'>License Count</a>";
                            }
                            else
                            {
                                echo "<a href='".$ADMIN_SITE_ROOT."reports/rpt_license_count.php' class=''>License Count</a>";
                            }
                            ?>
                        </li>
                        <li>
                           <?php
                            if ($ADM_CONST_PAGEID == "plan_expiry")
                            {
                                echo "<a href='".$ADMIN_SITE_ROOT."reports/rpt_plan_expiry.php' class='active'>Plan Expiry</a>";
                            }
                            else
                            {
                                echo "<a href='".$ADMIN_SITE_ROOT."reports/rpt_plan_expiry.php' class=''>Plan Expiry</a>";
                            }
                            ?>
                        </li>
                        <li>
                           <?php
                            if ($ADM_CONST_PAGEID == "meeting_count")
                            {
                                echo "<a href='".$ADMIN_SITE_ROOT."reports/rpt_meeting_count.php' class='active'>Meeting Report</a>";
                            }
                            else
                            {
                                echo "<a href='".$ADMIN_SITE_ROOT."reports/rpt_meeting_count.php' class=''>Meeting Report</a>";
                            }
                            ?>
                        </li>
                    </ul>
                <?php } ?>
            </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>
</div>
