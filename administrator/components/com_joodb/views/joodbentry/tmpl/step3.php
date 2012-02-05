<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<div id="content-box" style="border: 1px solid #ccc;">
<div class="padding">
<div id="toolbar-box">
	<div class="m">
		<?php
			echo $this->bar->render();
			$app = JFactory::getApplication();
			echo $app->get('JComponentTitle');
		 ?>
		<div class="clr"></div>
	</div>
</div>
<div id="element-box">
	<div class="m" style="font-size: larger; font-weight: bolder; height: 180px;">
	<?php echo JText::_( "Step3 Help" );   ?>
	<br/><br/><br/><br/>
	<div class="clr"></div>
	</div>
</div>
<br/>
</div>

</div>
</div>
<br/>
<script type="text/javascript" language="JavaScript">
if (window.Joomla !== undefined)
	Joomla.submitbutton = function(pressbutton) { submitbutton(pressbutton);  }

function submitbutton(pressbutton) {
	window.parent.location.reload();
}

</script>