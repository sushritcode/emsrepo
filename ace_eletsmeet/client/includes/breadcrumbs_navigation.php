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
        <a href="<?php echo $CLIENT_SITE_ROOT; ?>">Home</a>
    </li>
    
    <?php if ($CLIENT_CONST_MODULE == "cl_dashboard"){ ?>
        <li class="active">Dashboard</li>
    <?php }  ?>
        
    <?php if ($CLIENT_CONST_MODULE == "cl_profile"){ ?>
        <li class="active">Profile</li>
    <?php } ?>
    
    <?php if ($CLIENT_CONST_MODULE == "cl_user"){ ?>
        <li class="active">User</li>
    <?php }  ?>
        
    <?php if ($CLIENT_CONST_MODULE == "cl_subscription"){ ?>
        <li class="active">Subscription</li>
    <?php }  ?>        
      
    <?php if ($CLIENT_CONST_MODULE == "cl_reports"){ ?>
        <li class="">Reports</li>
        <?php if ( ($CLIENT_CONST_PAGEID=="Meeting Report") || ($CLIENT_CONST_PAGEID=="Meeting List") ){ ?>
            <li class="active"> Meeting Reports</li>
        <?php } ?>
        <?php if($CLIENT_CONST_PAGEID=="Subscription Report") { ?>
            <li class="active">Subscription Report</li>
        <?php } ?>
    <?php } ?>
 
            
   
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



