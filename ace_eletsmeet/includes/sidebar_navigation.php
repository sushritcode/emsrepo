<?php
if ($CONST_MODULE=="dashboard")
{
        $strDashBoardActiveClass= "active";
}
else
{ 
    $strDashBoardActiveClass= "";
}

if ($CONST_MODULE=="contacts")
{
        $strContactsActiveClass= "active open";
}
else
{ 
    $strContactsActiveClass= "";
}

if ( ($CONST_MODULE=="meeting") || ($CONST_MODULE=="schedule") )
{
        $strMeetingActiveClass= "active open";
}
else
{ 
    $strMeetingActiveClass= "";
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
                        <span style="font-size: 24px;"><small>Le</small></span>
                    </button>

                    <button class="btn btn-info">
                        <span style="font-size: 24px;"><small>ts</small></span>
                    </button>

                    <button class="btn btn-warning">
                        <span style="font-size: 24px;"><small>Me</small></span>
                    </button>

                    <button class="btn btn-danger">
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
                
                <li class="<?php echo $strDashBoardActiveClass;?>">
                    <a href="<?php echo $SITE_ROOT; ?>dashboard/">
                        <i class="menu-icon fa fa-tachometer"></i>
                        <span class="menu-text"> Dashboard </span>
                    </a>
                    <b class="arrow"></b>
                </li>
                
                <!-- Address book start-->
                <li class="<?php echo $strContactsActiveClass;?>">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-users"></i>
                        <span class="menu-text">
                            Contacts
                        </span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        <?php if($CONST_PAGEID=="My Contacts") { ?>
                            <li class="active">
                                <a href="<?php echo $SITE_ROOT; ?>contacts/">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    My Contacts
                                </a>
                                <b class="arrow"></b>
                            </li>
                        <?php }else{?>
                            <li class="">
                                <a href="<?php echo $SITE_ROOT; ?>contacts/">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    My Contacts
                                </a>
                                <b class="arrow"></b>
                            </li>
                        <?php } ?>
                            
                        <?php if($CONST_PAGEID=="Contacts Import") { ?>
                            <li class="active">
                                <a href="<?php echo $SITE_ROOT; ?>contacts/contactimport.php">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Import Contacts
                                </a>
                                <b class="arrow"></b>
                            </li>
                        <?php }else{?>
                            <li class="">
                                <a href="<?php echo $SITE_ROOT; ?>contacts/contactimport.php">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Import Contacts
                                </a>
                                <b class="arrow"></b>
                            </li>
                        <?php } ?>  
                    </ul>
                </li>
                <!-- Address book end -->
                
                <!-- Meetings book start-->
                <li class="<?php echo $strMeetingActiveClass;?>">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-calendar"></i>
                        <span class="menu-text">
                            Meetings
                        </span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>

                    <b class="arrow"></b>

                    <ul class="submenu">
                        <?php if($CONST_PAGEID=="Schedule Page") { ?>
                            <li class="active">
                                <a href="<?php echo $SITE_ROOT; ?>schedule/">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Schedule Meeting
                                </a>
                                <b class="arrow"></b>
                            </li>
                        <?php }else{?>
                            <li class="">
                                <a href="<?php echo $SITE_ROOT; ?>schedule/">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Schedule Meeting
                                </a>
                                <b class="arrow"></b>
                            </li>
                        <?php } ?>
                        
                       <?php if($CONST_PAGEID=="Scheduled Meeting") { ?>     
                            <li class="active">
                                <a href="<?php echo $SITE_ROOT; ?>meeting/">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    My Meeting
                                </a>
                                <b class="arrow"></b>
                            </li>
                        <?php }else{?>
                            <li class="">
                               <a href="<?php echo $SITE_ROOT; ?>meeting/">
                                   <i class="menu-icon fa fa-caret-right"></i>
                                   My Meeting
                               </a>
                               <b class="arrow"></b>
                           </li>
                        <?php } ?>
                           
                        <?php if($CONST_PAGEID=="Archived Meeting") { ?>
                        <li class="active">
                            <a href="<?php echo $SITE_ROOT; ?>meeting/archive.php">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Archive Meeting
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <?php }else{?>
                        <li class="">
                            <a href="<?php echo $SITE_ROOT; ?>meeting/archive.php">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Archive Meeting
                            </a>
                            <b class="arrow"></b>
                        </li>
                         <?php } ?>
                    </ul>
                </li>
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
