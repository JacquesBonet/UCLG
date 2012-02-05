<div class="item-page<?php echo $this->params->get('pageclass_sfx')?>">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');

	// get the parts
	$parts = JoodbHelper::splitTemplate($this->joobase->tpl_print);
	// parse the template
	$page = new JObject();
	$page->text = JoodbHelper::parseTemplate($this->joobase, $parts, $this->item,$this->params);
	// render output text
	JoodbHelper::printOutput($page,$this->params);

?>
</div>
<script type="text/javascript">
<!--
window.addEvent('load', function() {
  printnow = confirm('Print page ?');
  if (printnow) window.print();
});
//-->
</script>


