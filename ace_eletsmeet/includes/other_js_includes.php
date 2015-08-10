<?php
?>
<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
  <script type="text/javascript" src="<?php echo JS_PATH; ?>excanvas.js"></script>
<![endif]-->

<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery-ui.custom.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery.ui.touch-punch.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery.easypiechart.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery.sparkline.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>flot/jquery.flot.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>flot/jquery.flot.pie.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>flot/jquery.flot.resize.js"></script>

<!-- ace scripts -->
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/elements.scroller.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/elements.colorpicker.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/elements.fileinput.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/elements.typeahead.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/elements.wysiwyg.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/elements.spinner.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/elements.treeview.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/elements.wizard.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/elements.aside.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/ace.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/ace.ajax-content.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/ace.touch-drag.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/ace.sidebar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/ace.sidebar-scroll-1.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/ace.submenu-hover.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/ace.widget-box.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/ace.settings.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/ace.settings-rtl.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/ace.settings-skin.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/ace.widget-on-reload.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH; ?>ace/ace.searchbox-autocomplete.js"></script>

<!-- inline scripts related to this page -->
<script type="text/javascript">
    jQuery(function ($) {



        $('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
        function tooltip_placement(context, source) {
            var $source = $(source);
            var $parent = $source.closest('.tab-content')
            var off1 = $parent.offset();
            var w1 = $parent.width();

            var off2 = $source.offset();
            //var w2 = $source.width();

            if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2))
                return 'right';
            return 'left';
        }


        $('.dialogs,.comments').ace_scroll({
            size: 300
        });


        //Android's default browser somehow is confused when tapping on label which will lead to dragging the task
        //so disable dragging when clicking on label
        var agent = navigator.userAgent.toLowerCase();
        if ("ontouchstart" in document && /applewebkit/.test(agent) && /android/.test(agent))
            $('#tasks').on('touchstart', function (e) {
                var li = $(e.target).closest('#tasks li');
                if (li.length == 0)
                    return;
                var label = li.find('label.inline').get(0);
                if (label == e.target || $.contains(label, e.target))
                    e.stopImmediatePropagation();
            });

        $('#tasks').sortable({
            opacity: 0.8,
            revert: true,
            forceHelperSize: true,
            placeholder: 'draggable-placeholder',
            forcePlaceholderSize: true,
            tolerance: 'pointer',
            stop: function (event, ui) {
                //just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
                $(ui.item).css('z-index', 'auto');
            }
        }
        );

        $('#tasks').disableSelection();
        $('#tasks input:checkbox').removeAttr('checked').on('click', function () {
            if (this.checked)
                $(this).closest('li').addClass('selected');
            else
                $(this).closest('li').removeClass('selected');
        });


        //show the dropdowns on top or bottom depending on window height and menu position
        $('#task-tab .dropdown-hover').on('mouseenter', function (e) {
            var offset = $(this).offset();

            var $w = $(window)
            if (offset.top > $w.scrollTop() + $w.innerHeight() - 100)
                $(this).addClass('dropup');
            else
                $(this).removeClass('dropup');
        });

    })
</script>