<?php
if ($CLIENT_CONST_MODULE == "cl_dashboard") {
    $strDashBoardActiveClass = "active";
} else {
    $strDashBoardActiveClass = "";
}

if ($CLIENT_CONST_MODULE == "cl_user") {
    $strUserActiveClass = "active";
} else {
    $strUserActiveClass = "";
}


if ($CLIENT_CONST_MODULE == "cl_reports") {
    $strReportActiveClass = "active";
} else {
    $strReportActiveClass = "";
}
?>

<div class="">
    <div class="main-container" id="main-container">
        <script type="text/javascript">
            try {
                ace.settings.check('main-container', 'fixed')
            } catch (e) {
            }
        </script>

        
        <div id="sidebar" class="sidebar                  responsive">
            <script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'fixed')
                } catch (e) {
                }
            </script>

            <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                    <button class="btn btn-success">
                        <i class="ace-icon fa fa-signal"></i>
                    </button>

                    <button class="btn btn-info">
                        <i class="ace-icon fa fa-pencil"></i>
                    </button>

                   
                    <button class="btn btn-warning">
                        <i class="ace-icon fa fa-users"></i>
                    </button>

                    <button class="btn btn-danger">
                        <i class="ace-icon fa fa-cogs"></i>
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
                
                <li class="<?php echo $strReportActiveClass; ?>">
                    <a href="<?php echo $CLIENT_SITE_ROOT; ?>reports/">
                        <i class="menu-icon fa fa-bar-chart-o"></i>
                        <span class="menu-text"> Reports </span>
                    </a>
                    <b class="arrow"></b>
                </li>



                <!-- Meetings book start-->
<!--                <li class="<?php echo $strMeetingActiveClass; ?>">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-calendar"></i>
                        <span class="menu-text">
                            Meetings
                        </span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>

                    <b class="arrow"></b>

                    <ul class="submenu">
                    <?php if ($CONST_PAGEID == "Schedule Page") { ?>
                            <li class="active">
                                <a href="<?php echo $SITE_ROOT; ?>schedule/">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Schedule Meeting
                                </a>
                                <b class="arrow"></b>
                            </li>
                        <?php } else { ?>
                            <li class="">
                                <a href="<?php echo $SITE_ROOT; ?>schedule/">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Schedule Meeting
                                </a>
                                <b class="arrow"></b>
                            </li>
                        <?php } ?>

                        

                        
                    </ul>
                </li>-->
                <!-- Meetings book end-->
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
        </div>
    </div>
</div>
