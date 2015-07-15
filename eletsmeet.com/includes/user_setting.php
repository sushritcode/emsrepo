<?php
/*
 * User setting option Code for EMeet, like Logout and Profile.
 */
?>      
    <?php
    if (strlen(trim($strCk_email_address)) > 0)
    {
    ?>
        <div class="span6 pL75 fR">
            <div class="fR s13"><em>Welcome&nbsp;</em> 
                <span class="cBk"><?php echo $strCk_nick_name; ?></span>
                <span class="mL5"><a href="<?php echo $SITE_ROOT."profile/" ?> " class="btn btn-small"><i class="icon-cog"></i>&nbsp;My Profile</a></span>
                <span class="mL10"><a href="<?php echo LOGOUT_URL; ?>" class="btn btn-primary"><i class='icon-white icon-off'></i>&nbsp;Logout</a></span>
            </div>
        </div>
     <?php
    }
    else
    {?>
        <div class="span4 pL75"><div class="fR s13">&nbsp;</div></div>
    <?php
    }
    ?>