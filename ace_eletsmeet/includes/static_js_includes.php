<?php
?>
<!-- basic scripts -->

<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo JS_PATH; ?>jquery.js'>" + "<" + "/script>");
</script>
<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo JS_PATH; ?>jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->

<script type="text/javascript">
    if ('ontouchstart' in document.documentElement)
        document.write("<script src='<?php echo JS_PATH; ?>jquery.mobile.custom.js'>" + "<" + "/script>");
</script>

<script type="text/javascript" src="<?php echo JS_PATH; ?>bootstrap.js"></script>



