<?php ?>
<script type="text/javascript">
    try {
        ace.settings.check('breadcrumbs', 'fixed')
    } catch (e) {
    }
</script>
<!-- /.breadcrumb -->
<ul class="breadcrumb">
    <li>
        <i class="ace-icon fa fa-home home-icon"></i>
        <a href="<?php echo $SITE_ROOT; ?>">Home</a>
    </li>
    <?php if ($CONST_MODULE == "dashboard"){ ?>
        <li class="active">Dashboard</li>
    <?php }  ?>
        
    <?php if ($CONST_MODULE == "profile"){ ?>
        <li class="active">Profile</li>
    <?php } ?>
    
    <?php if ( ($CONST_MODULE=="meeting") || ($CONST_MODULE=="schedule") ){ ?>
        <li class="">Meetings</li>
        <?php if($CONST_PAGEID=="Schedule Page") { ?>
            <li class="active">Schedule Meeting</li>
        <?php } ?>
        <?php if($CONST_PAGEID=="Scheduled Meeting") { ?>
            <li class="active">My Meeting</li>
        <?php } ?>
        <?php if($CONST_PAGEID=="Archived Meeting") { ?>
            <li class="active">Archive Meeting</li>
        <?php } ?>
    <?php } ?>
    
    <?php if ($CONST_MODULE == "contact"){ ?>
        <li class="">Contact</li>
        <?php if($CONST_PAGEID=="My Contacts") { ?>
            <li class="active">My Contacts</li>
        <?php } ?>
        <?php if($CONST_PAGEID=="Contacts Import") { ?>
            <li class="active">Import Addresses (csv)</li>
        <?php } ?>
    <?php } ?>
            
            <?php if ($CONST_MODULE == "join"){ ?>
        <li class="active">Join Meeting</li>
    <?php }  ?>
    
</ul><!-- /.breadcrumb -->

<!-- #section:basics/content.searchbox -->
<!--<div class="nav-search" id="nav-search">
    <form class="form-search">
        <span class="input-icon">
            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
            <i class="ace-icon fa fa-search nav-search-icon"></i>
        </span>
    </form>
</div>-->
<!-- /section:basics/content.searchbox -->



