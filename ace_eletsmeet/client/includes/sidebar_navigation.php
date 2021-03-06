<?php
if ($CLIENT_CONST_MODULE == "cl_dashboard")
{
    $strDashBoardActiveClass = "active";
}
else
{
    $strDashBoardActiveClass = "";
}

if ($CLIENT_CONST_MODULE == "cl_user")
{
    $strUserActiveClass = "active";
}
else
{
    $strUserActiveClass = "";
}

if ($CLIENT_CONST_MODULE == "cl_contacts")
{
    $strContactActiveClass = "active open";
}
else
{
    $strContactActiveClass = "";
}

if ($CLIENT_CONST_MODULE == "cl_subscription")
{
    $strSubscriptionActiveClass = "active";
}
else
{
    $strSubscriptionActiveClass = "";
}


if ($CLIENT_CONST_MODULE == "cl_reports")
{
    $strReportActiveClass = "active open";
}
else
{
    $strReportActiveClass = "";
}
?>

<script type="text/javascript">
    try {
        ace.settings.check('sidebar', 'fixed')
    } catch (e) {
    }
</script>

<div class="sidebar-shortcuts" id="sidebar-shortcuts">
    <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
        <button class="btn btn-success">
<!--            <i class="ace-icon fa fa-signal"></i>-->
            <span style="font-size: 24px;"><small>Le</small></span>
        </button>

        <button class="btn btn-info">
<!--            <i class="ace-icon fa fa-pencil"></i>-->
            <span style="font-size: 24px;"><small>ts</small></span>
        </button>


        <button class="btn btn-warning">
<!--            <i class="ace-icon fa fa-users"></i>-->
            <span style="font-size: 24px;"><small>Me</small></span>
        </button>

        <button class="btn btn-danger">
<!--            <i class="ace-icon fa fa-cogs"></i>-->
            <span style="font-size: 24px;"><small>et</small></span>
        </button>


    </div>

    <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
        <span class="btn btn-success"></span>

        <span class="btn btn-info"></span>

        <span class="btn btn-warning"></span>

        <span class="btn btn-danger"></span>
    </div>
</div>

<ul class="nav nav-list">
    <li class="<?php echo $strDashBoardActiveClass; ?>">
        <a href="<?php echo $CLIENT_SITE_ROOT; ?>dashboard/">
            <i class="menu-icon fa fa-tachometer"></i>
            <span class="menu-text"> Dashboard </span>
        </a>
        <b class="arrow"></b>
    </li>

    <li class="<?php echo $strUserActiveClass; ?>">
        <a href="<?php echo $CLIENT_SITE_ROOT; ?>user/">
            <i class="menu-icon fa fa-users"></i>
            <span class="menu-text"> User </span>
        </a>
        <b class="arrow"></b>
    </li>
    
    <li class="<?php echo $strContactActiveClass; ?>">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-user"></i>
            <span class="menu-text">Contacts</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">
            <?php if ($CLIENT_CONST_PAGEID == "My Contacts") { ?>
                <li class="active">
                    <a href="<?php echo $CLIENT_SITE_ROOT; ?>contacts/">
                        <i class="menu-icon fa fa-caret-right"></i>
                        My Contacts
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php }
                else
                { ?>
                <li class="">
                    <a href="<?php echo $CLIENT_SITE_ROOT; ?>contacts/">
                        <i class="menu-icon fa fa-caret-right"></i>
                        My Contacts
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php } ?>

                <?php if ($CLIENT_CONST_PAGEID == "Contacts Import")
                { ?>
                <li class="active">
                    <a href="<?php echo $CLIENT_SITE_ROOT; ?>contacts/contactimport.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Import Contacts 
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php }
                else
                { ?>
                <li class="">
                    <a href="<?php echo $CLIENT_SITE_ROOT; ?>contacts/contactimport.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Import Contacts
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php } ?>
        </ul>
    </li>

    <li class="<?php echo $strSubscriptionActiveClass; ?>">
        <a href="<?php echo $CLIENT_SITE_ROOT; ?>subscription/">
            <i class="menu-icon fa fa-file-text"></i>
            <span class="menu-text"> Subscription </span>
        </a>
        <b class="arrow"></b>
    </li>

    
    <li class="<?php echo $strReportActiveClass; ?>">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-bar-chart-o"></i>
            <span class="menu-text">Reports</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">
            <?php if (($CLIENT_CONST_PAGEID == "Meeting Report") || ($CLIENT_CONST_PAGEID == "Meeting List"))
            { ?>
                <li class="active">
                    <a href="<?php echo $CLIENT_SITE_ROOT; ?>reports/">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Meeting Report
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php }
                else
                { ?>
                <li class="">
                    <a href="<?php echo $CLIENT_SITE_ROOT; ?>reports/">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Meeting Report
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php } ?>

                <?php if ($CLIENT_CONST_PAGEID == "Subscription Report")
                { ?>
                <li class="active">
                    <a href="<?php echo $CLIENT_SITE_ROOT; ?>reports/subscription.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Subscription Report
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php }
                else
                { ?>
                <li class="">
                    <a href="<?php echo $CLIENT_SITE_ROOT; ?>reports/subscription.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Subscription Report
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php } ?>
        </ul>
    </li>
    
    
</ul>


<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
    <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
</div>


<script type="text/javascript">
    try {
        ace.settings.check('sidebar', 'collapsed')
    } catch (e) {
    }
</script>


