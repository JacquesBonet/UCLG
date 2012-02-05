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
<jdoc:include type="message" />
<div id="element-box">
	<div class="m">
		<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data" target="_top">
			<input type="hidden" name="tmpl" value="component" />
			<input type="hidden" name="option" value="com_joodb" />
			<input type="hidden" name="task" value="import" />
			<?php echo JHTML::_( 'form.token' );?>
			<table cellpadding="4" cellspacing="1" border="0" class="paramlist admintable" >
			<tr>
				<td class="paramlist_key"><label for="tablename"><?php echo JText::_( "Destination Tablename" ); ?></label></td>
				<td><input type="text" class="inputbox required" id="tablename" name="tablename"  value="" style="width: 350px" /></td>
			</tr>
			<tr>
				<td class="paramlist_key"><label for="tablefile"><?php echo JText::_( "Excel File" ); ?></label></td>
				<td>
					<input class="inputbox" name="tablefile" id="tablefile" type="file" size="28" style="width: 350px; display:block;" accept="*.xls,.xlsx,.xlsm,.xlsb,*.xlm">
					<?php echo "<i>".JText::_("Upload valid Excel file")."</i>" ?>
				</td>
			</tr>
			<tr>
				<td class="paramlist_key"><?php echo JText::_( "Column Names in first line" ); ?></td>
				<td>
					<input type="radio" name="has_column_names" value="0" checked="checked"><?php echo JText::_( "JNo" ); ?>
					<input type="radio" name="has_column_names" value="1"><?php echo JText::_( "JYes" ); ?>
				</td>
			</tr>
		</table>
	</form>
	</div>
</div>

	<br/>
</div>
</div>
<script language="JavaScript">

//Send Form
if (window.Joomla !== undefined)
	Joomla.submitbutton = function(pressbutton) { submitbutton(pressbutton);  }

function submitbutton(pressbutton) {
	var form = document.adminForm;
	form.task = pressbutton;
	// do field validation
	if (form.tablefile.value == "") {
		  alert('<?php echo JText::_( "Fillout required fields" ); ?>');
		  return false;
	}
	// do field validation
     if (document.formvalidator.isValid(form)) {
 // Test if table exists
<?php if ($this->version->RELEASE=="1.5") : ?>
	new Ajax('index.php?option=com_joodb&task=testtable', { async: false,
<?php else : ?>
	new Request.JSON({'url' :'index.php?option=com_joodb&task=testtable', async: false,
<?php endif; ?>
		onSuccess: function(response) {
<?php if ($this->version->RELEASE=="1.5") : ?> response = Json.evaluate(response); <?php endif; ?>
			if (response==true) {
				check = confirm("<?php  echo JText::_("Table exist"); ?>");
				if (check == false) return false;
			}
			document.adminForm.submit();
			return true;
		}
   	}).<?php echo ($this->version->RELEASE=="1.5") ? "request" : "get"; ?>({'table': form.tablename.value });
 } else alert('<?php echo JText::_( "Fillout required fields" ); ?>');
	  return false;
}

</script>


