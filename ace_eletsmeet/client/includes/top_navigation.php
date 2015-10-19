<?php 
?>

<script type="text/javascript">
    try 
    {
        ace.settings.check('navbar', 'fixed')
    } 
    catch (e) 
    {
    }
</script>
<div class="navbar-container" id="navbar-container">
    
    <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
        <span class="sr-only">Toggle sidebar</span>

        <span class="icon-bar"></span>

        <span class="icon-bar"></span>

        <span class="icon-bar"></span>
    </button>
    
    <div class="navbar-header pull-left">
        <a href="<?php echo $CLIENT_SITE_ROOT;?>" class="navbar-brand">
            <small><i class="fa fa-leaf"></i>&nbsp;LetsMeet</small>
        </a>
    </div>

    <div class="navbar-buttons navbar-header pull-right" role="navigation">
        <ul class="nav ace-nav">
            <?php if (strlen(trim($strCk_email_address)) > 0) { ?>
            <li class="light-blue">
                <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                    <img class="nav-user-photo" src="<?php echo AVATARS_PATH; ?>avatar2.png" alt="<?php echo $strCk_email_address; ?>'s Photo" title="<?php echo $strCk_email_address; ?>'s Photo" />
                    <span class="user-info"><small>Welcome,</small><?php echo $strCk_email_address; ?></span>
                    <i class="ace-icon fa fa-caret-down"></i>
                </a>
                <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
<!--                    <li>
                        <a href="#"><i class="ace-icon fa fa-cog"></i>Settings</a>
                    </li>-->
                    <li>
                        <a href="<?php echo PROFILE_URL; ?>"><i class="ace-icon fa fa-user"></i>Profile</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?php echo CLIENT_LOGOUT_URL; ?>"><i class="ace-icon fa fa-power-off"></i>Logout</a>
                    </li>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </div>
    
</div>