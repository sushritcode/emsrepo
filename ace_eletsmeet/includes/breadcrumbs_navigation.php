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



