<?php

$Current_GMT_Date = date("Y-m-d", strtotime(GM_DATE));

try
{
    $arrUserSubDetails = getUserSubscriptionDetails($strCK_user_id, $Current_GMT_Date, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("Error in isAuthenticateSubscription.".$a->getMessage(), 311);
}

//echo "<pre/>";
//print_r($arrUserSubDetails);
//echo "<pre/>";


    ?>
    <div class="well">
        <h2>Account Status</h2>
        
        <?php
        if (is_array($arrUserSubDetails) && sizeof($arrUserSubDetails) > 0)
        {

            for ($w = 0; $w < sizeof($arrUserSubDetails); $w++)
            {
                $Sub_End_Date_GMT = $arrUserSubDetails[$w]['subscription_end_date_gmt'];
        ?>
        
                <ul class='meetingschedule'>
                    <li>
                        <div>
                            <div class="s16 cBk"><?php echo $arrUserSubDetails[$w]['plan_name']; ?></div>
                            <div class="cGyl">Valid till <?php echo $arrUserSubDetails[$w]['subscription_end_date_local']; ?></div>
                        </div>
                    </li>
                </ul>
        
                
        
                <!--div class="cGyl pB15">
                    <?php
                    if ($Sub_End_Date_GMT > $Current_GMT_Date)
                    {
                        ?>

                        <div><span style ="color:#468847;"><strong><?php echo $arrUserSubDetails[$w]['plan_name']; ?></strong></span></div>

                        <?php
                    }
                    else
                    {
                        ?>

                        <div><span style ="color:#B94A48;"><strong><?php echo $arrUserSubDetails[$w]['plan_name']; ?></strong></span></div>

                    <?php } ?>


                    
                    <div class="mB10 brdbdGy mT10">Valid till <?php echo $arrUserSubDetails[$w]['subscription_end_date_local']; ?></div>
                    
                </div-->

                <?php
            }?>
                
                <div class="pB5 mT10"><a href="<?php echo $SITE_ROOT."subscribe/subscription_info.php"; ?>" class="btn btn-success">View Details</a></div>
                    
            
        <?php 
        }
        else
        { ?>
            <div class='alert alert-error mT10'>You are not subscribed to any plan. Please contact administrator to subscribe.</div>
        <?php 
        }
        ?>
    </div>
        
