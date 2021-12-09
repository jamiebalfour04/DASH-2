      <div class="group" id="footer">
        DASH 2.0 (preview) | © Jamie Balfour 2014 - 2018, 2021
      </div>
    </div>
    <div id="console">
      <div contenteditable="true" id="console_input">
        <div></div>
      </div>
      <div id="console_result"></div>
    </div>
  </div>
  <div id="menu_unselectable"></div>
</div>
<div id="ajax_loader">
  <img src="/dash_old//dashboard/images/ajax_black.gif" alt="Loading">
</div>
<?php
require_once dirname(__FILE__).'/../editor/tinymce/main.php';
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/4.10.0/d3.min.js"></script>
<script src="<?php echo DASHBOARD_PATH.'ui/js/balfpick.js'; ?>"></script>
<script src="<?php echo DASHBOARD_PATH.'ui/js/script.js'; ?>"></script>
<script src="<?php echo DASHBOARD_PATH.'ui/js/forms.js'; ?>"></script>
<script>
$('select.balfpick').BalfPick();
</script>

</body>
</html>
