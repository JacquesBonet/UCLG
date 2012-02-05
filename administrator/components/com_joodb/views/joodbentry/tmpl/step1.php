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
	<div class="m" style="font-size: larger; height: 180px;">
	<form name="adminForm" action="index.php"  class="form-validate"  method="post" >
		<input type="hidden" name="option" value="com_joodb" />
		<input type="hidden" name="server" value="<?php echo JREquest:: getVar("server");?>" />
		<input type="hidden" name="user" value="<?php echo JREquest:: getVar("user");?>" />
		<input type="hidden" name="pass" value="<?php echo JREquest:: getVar("pass");?>" />
		<input type="hidden" name="database" value="<?php echo JREquest:: getVar("database");?>" />
		<input type="hidden" name="view" value="joodbentry" />
		<input type="hidden" name="tmpl" value="component" />
		<input type="hidden" name="layout" value="step2" />
		<input type="hidden" name="task" value="addnew" />
		<table cellpadding="5">
		  <tr>
			<td><?php echo JText::_( "Name Your DB" ); ?></td>
			<td>
			<input type="text" value="" class="inputbox required" name="dbname" style="width:250px;"/>
			</td>
		  </tr><tr><td><?php echo JText::_( "Please choose table" ); ?></td>
		  <td>
			<select name="dbtable" class="inputbox required" style="width:250px;" >
		 		<option value="">...</option>
				<?php
					foreach ($this->tables as $table) {
						echo "<option>".$table."</option>";
					}
				?>
			</select>
		   </td>
		</tr>
<?php if ($this->version->RELEASE!="1.5") : ?>
		<tr><td>&nbsp;</td>
		<td><button type="button" onmousedown="submitbutton('extern');"><?php echo JText :: _('Use External Database'); ?></button>
		</td>
		</tr>
<?php endif; ?>
		</table>
	</form>
	<br/>
	<div class="clr"></div>
	</div>
</div>
<br/>
</div>
</div>
<br/>
<script type="text/javascript" language="JavaScript">
//Send Form

if (window.Joomla !== undefined)
	Joomla.submitbutton = function(pressbutton) { submitbutton(pressbutton);  }

function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton=="extern") {
			form.layout.value="extern";
			form.submit();
			return false;
		}
		if (!document.formvalidator.isValid(form)) {
			if(form.dbname.value==""){
				alert('<?php echo JText::_( "Name Your DB" ); ?>');
				return false;
			}
			if(form.dbtable.selectedIndex<=0){
				alert('<?php echo JText::_( "Please choose table" ); ?>');
				return false;
			}
		}

		form.submit();
	}
</script>

