<?php if (strlen(trim($strCk_email_address)) > 0) { ?>
    <div class="pL75 fR">
        <div class="fR s13"><em>Welcome&nbsp;</em> 
            <span class="cBk"><?php echo $strCk_email_address; ?></span>
            <span class="mL5"><a href="<?php echo $CLIENT_SITE_ROOT . "profile/" ?> " class="btn btn-small"><i class="icon-cog"></i> My Profile</a></span>
            <span class="mL10"><a href="<?php echo CLIENT_LOGOUT_URL; ?>" class="btn btn-primary"><i class='icon-white icon-off'></i>&nbsp;Logout</a></span>
        </div>
    </div>
<?php } else { ?>
    <div class="span4 pL75"><div class="fR s13">&nbsp;</div></div>
<?php } ?>
