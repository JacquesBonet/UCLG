<?php

// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');

$params = $this->params;
$item = $this->item;
$fields =$this->fields;
// 	Load the JEditor object
$editor =& JFactory::getEditor();

$sliders = &JPane::getInstance('sliders', array('allowAllClose' => false));
$tabs = &JPane::getInstance('sliders', array('useCookie' => true,'allowAllClose' => true));


?>
<style type="text/css">
<!--
	table.admintable {
		width: 100%;
		margin: 10px;
	}

	div.current {
		padding: 0;
	}

	.paramlist_key {
		vertical-align: middle;
		padding: 5px;
	}

-->
</style>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
	<input type="hidden" name="option" value="com_joodb" />
	<input	type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="joodb" />
	<input type="hidden" name="id" value="<?php echo $item->id; ?>" />
	<?php echo JHtml::_('form.token'); ?>
	<div id="config-document"  class="width-60 fltlft" style="width:60%; float:left;" >
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Database' ); ?></legend>
<?php
	echo $tabs->startPane("config-pane");
	echo $tabs->startPanel(JText :: _('General options'), "param-options");
 ?>
		<table class="paramlist admintable">
			<tr>
				<td width="250" class="paramlist_key"><?php echo JText::_( 'Database Name' ); ?>:</td>
				<td class="paramlist_value">
					<input class="inputbox required" type="text" name="name" value='<?php echo str_replace("\'","\"",$item->name); ?>' maxlength="50" size="50" style="width: 250px" />
				</td>
			</tr>
			<tr>
				<td width="250" class="paramlist_key"><?php echo JText::_( 'Table' ); ?>:</td>
				<td class="paramlist_value">
					<select name="table" class="inputbox required"  onchange="submitbutton('apply');" style="width: 250px" ><?php
						foreach ($this->tables as $table) {
							echo "<option".(($table==$item->table) ? " selected" : "").">".$table."</option>";
						}
					?></select>
				</td>
			</tr>
			<tr>
				<td >&nbsp;</td>
				<td class="paramlist_value"><br/><b><?php echo JText::_( "Special fields" ); ?></b></td>
			</tr>
			<tr>
				<td width="250" class="paramlist_key"><?php echo JText::_( "Primary Index" ); ?>:</td>
				<td class="paramlist_value">
<?php
				$fselect = JooDBAdminHelper::selectFieldTypes("primary",$fields);
				echo '<select name="fid"  class="inputbox"  style="width: 250px" >';
				foreach ($fselect as $fname) {
						echo "<option".(($fname==$item->fid) ? " selected" : "").">".$fname."</option>";
				}
				echo "</select>";
				if (count($fselect)<1)
					echo '<p style="color: #d40000; font-weight: bold; clear:both;">'.JText::_( "No Primary Index" ).'</p>';
?>
				</td>
			</tr>
			<tr>
				<td width="250" class="paramlist_key"><?php echo JText::_( "Title or Headline" ) ?>:</td>
				<td class="paramlist_value">
					<select name="ftitle"  class="inputbox"  style="width: 250px" ><?php
						$fselect = JooDBAdminHelper::selectFieldTypes("shorttext",$fields);
						foreach ($fselect as $fname) {
							echo "<option".(($fname==$item->ftitle) ? " selected" : "").">".$fname."</option>";
						}
					 ?>	</select>
				</td>
			</tr>
			<tr>
				<td width="250" class="paramlist_key"><?php echo JText::_( "Main Content" ); ?>:</td>
				<td class="paramlist_value">
					<select name="fcontent"  class="inputbox"  style="width: 250px" ><?php
						foreach ($fselect as $fname) {
							echo "<option".(($fname==$item->fcontent) ? " selected" : "").">".$fname."</option>";
						}
					 ?>	</select>
				</td>
			</tr>
			<tr>
				<td width="250" class="paramlist_key"><?php echo JText::_( "Abstract" ); ?>:</td>
				<td class="paramlist_value">
					<select name="fabstract"  class="inputbox"  style="width: 250px" >
						 <option value="">...</option><?php
						foreach ($fselect as $fname) {
							echo "<option".(($fname==$item->fabstract) ? " selected" : "").">".$fname."</option>";
						}
					 ?>	</select>
				</td>
			</tr>
			<tr>
				<td width="250" class="paramlist_key"><?php echo JText::_( "Main Date" ); ?>:</td>
				<td class="paramlist_value">
					<select name="fdate"  class="inputbox"  style="width: 250px" >
					 	<option value="">...</option><?php
						$fselect = JooDBAdminHelper::selectFieldTypes("date",$fields);
					 	foreach ($fselect as $fname) {
							echo "<option".(($fname==$item->fdate) ? " selected" : "").">".$fname."</option>";
						}
					 ?>	</select>
				</td>
			</tr>
			<tr>
				<td width="250" class="paramlist_key"><?php echo JText::_( "Status Field" ); ?>:</td>
				<td class="paramlist_value">
					<select name="fstate"  class="inputbox"  style="width: 250px" >
					 	<option value="">...</option><?php
						$fselect = JooDBAdminHelper::selectFieldTypes("number",$fields);
						foreach ($fselect as $fname) {
							echo "<option".(($fname==$item->fstate) ? " selected" : "").">".$fname."</option>";
						}
					 ?>	</select>
				</td>
			</tr>
		</table>
<?php
		echo $tabs->endPanel();
		echo $tabs->startPanel(JText :: _('Catalog template'), "config-cattmpl");
 ?>
		<table class="paramlist admintable">
			<tr>
				<td class="paramlist_value">
				<?php
					echo $editor->display('tpl_list', stripslashes($item->tpl_list), '95%', '500', '40', '6',false);
					JooDBAdminHelper::printTemplateFooter('tpl_list',$fields,'catalog');
				?>
				</td>
			</tr>
		</table>
<?php
		echo $tabs->endPanel();
		echo $tabs->startPanel(JText :: _('Singleview template'), "config-sngltmpl");
?>
		<table class="paramlist admintable">
			<tr>
				<td class="paramlist_value">
				<?php
					echo $editor->display('tpl_single', stripslashes($item->tpl_single),'95%', '500', '40', '6',false);
					JooDBAdminHelper::printTemplateFooter('tpl_single',$fields,'single');
				 ?>
				</td>
			</tr>
		</table>
<?php
		echo $tabs->endPanel();
		echo $tabs->startPanel(JText :: _('Print template'), "config-prnttmpl");
?>
		<table class="paramlist admintable">
			<tr>
				<td class="paramlist_value">
				<?php	// 	Load the JEditor object
					echo $editor->display('tpl_print', stripslashes($item->tpl_print), '95%', '500', '40', '6',false);
					JooDBAdminHelper::printTemplateFooter('tpl_print',$fields,'print');
				?>
				</td>
			</tr>
		</table>
<?php
		echo $tabs->endPanel();
		echo $tabs->startPanel(JText :: _('Form template'), "config-frmtmpl");
?>
		<table class="paramlist admintable">
			<tr>
				<td class="paramlist_value">
				<?php	// 	Load the JEditor object
					echo $editor->display('tpl_form', stripslashes($item->tpl_form),'95%', '500', '40', '6',false);
				 	JooDBAdminHelper::printTemplateFooter('tpl_form',$fields,'form');
				 ?>
				</td>
			</tr>
		</table>
<?php
		echo $tabs->endPanel();
		echo $tabs->endPane();
?>
</fieldset>
</div>
<div class="col width-40 fltrt" style="width:40%;">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Parameters' ); ?></legend>
		<?php
			echo $sliders->startPane("menu-pane");
			echo $sliders->startPanel(JText :: _('Access rights'), "param-page");
			echo '<div style="padding:5px;">'.$params->render().'</div>';
			echo $sliders->endPanel();
			echo $sliders->startPanel(JText :: _('Images'), "param-page");
			echo '<div style="padding:5px;">'.$params->render("params","images").'</div>';
			echo $sliders->endPanel();
			echo $sliders->startPanel(JText :: _('External Database'), "param-page");
			echo '<div style="padding:5px;">'.$params->render("params","database").'</div>';
			echo $sliders->endPanel();
			echo $sliders->endPane();
		?>
	</fieldset>
</div>
</form>
<script language="JavaScript">

//Send Form
if (window.Joomla !== undefined)
	Joomla.submitbutton = function(task) { submitbutton(task);  }

function submitbutton(task)
		{
			var form = document.adminForm;
			form.task.value = task;
			// tinymce is buggy!!!

			if (task == 'cancel') {
				if (window.Joomla) { Joomla.submitform(task, form); } else { form.submit(); }
				return true;
			}

			// do field validation
			if (form.name.value == ""){
				alert('<?php echo JText::_( "Name Your DB" ); ?>');
				form.title.focus();
				return false;
			} else  {
		        if ((form.table.value=="") && (!document.formvalidator.isValid(form))) return false;
				if (window.Joomla) { Joomla.submitform(task, form); } else { form.submit(); }
			}
			return false;
		}

</script>
