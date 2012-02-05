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
	<form name="adminForm" action="index.php"  class="form-validate" method="post" >
		<input type="hidden" name="option" value="com_joodb" />
		<input type="hidden" name="view" value="joodbentry" />
		<input type="hidden" name="tmpl" value="component" />
		<input type="hidden" name="layout" value="step1" />
		<input type="hidden" name="task" value="addnew" />
		<table cellpadding="5">
		  <tr>
			<td><?php echo JText :: _('Server'); ?></td>
			<td>
				<input type="text" value="" class="inputbox required" name="server" style="width:250px;"/>
			</td>
			<td rowspan="4" style="padding-left: 20px; text-align: center;">
				<img src="<?php echo JURI::root(); ?>administrator/components/com_joodb/assets/images/remote.png" alt="*"/>
				<div id="indicator1" style="margin: 5px 0; padding: 5px; background-color: #ccc; border: 1px solid #ccc; width: 150px; text-align: center;">...</div>
				<div id="indicator2" style="display: none; margin: 5px 0; padding: 5px; background-color: #d40000; color: #fff; border: 1px solid #ccc; width: 150px; text-align: center;"><?php echo JText :: _('Error'); ?></div>
				<div id="indicator3" style="display: none; margin: 5px 0; padding: 5px; background-color: #3c9103; color: #fff; border: 1px solid #ccc; width: 150px; text-align: center;"><?php echo JText :: _('Connection established'); ?></div>
				<button type="button" onmousedown="testConnection();"><?php echo JText :: _('Test Connection'); ?></button>
			</td>
		</tr>
	    <tr>
			<td><?php echo JText :: _('Username'); ?></td>
			<td>
				<input type="text" value="" class="inputbox required" name="user" style="width:250px;"/>
			</td>
		</tr>
	    <tr>
			<td><?php echo JText :: _('Password'); ?></td>
			<td>
				<input type="password" value="" class="inputbox required" name="pass" style="width:250px;"/>
			</td>
		</tr>
	    <tr>
			<td><?php echo JText :: _('Database'); ?></td>
			<td>
				<select class="inputbox" name="database" style="width:250px;">
				  <option value="">...</option>
				</select>
			</td>
		</tr>
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

if (window.Joomla !== undefined)
	Joomla.submitbutton = function(pressbutton) { submitbutton(pressbutton);  }

// test connection and list availiable databases
function testConnection() {
	var form = document.adminForm;
	var id = new Array('none','none');
	var success = false
	if (document.formvalidator.isValid(form)) {
	    $('indicator1').setStyle('display','none');
<?php if ($this->version->RELEASE=="1.5") : ?>
	new Ajax('index.php?option=com_joodb&task=testconnection', { async: false,
<?php else : ?>
	new Request.JSON({'url' :'index.php?option=com_joodb&task=testconnection', async: false,
<?php endif; ?>
	onSuccess: function(response) {
<?php if ($this->version->RELEASE=="1.5") : ?> response = Json.evaluate(response); <?php endif; ?>
		if (response.dbs) {
			success=true; id[1] = "block";
			form.database.options.length = 0;
			form.database.options[form.database.options.length] = new Option("...","");
			response.dbs.each(function(el){
				form.database.options[form.database.options.length] = new Option(el,el);
			});
		} else {
			success=false; id[0] = "block";
		}
	 }
   	}).<?php echo ($this->version->RELEASE=="1.5") ? "request" : "get"; ?>({'extdb_server': form.server.value, 'extdb_user': form.user.value, 'extdb_pass': form.pass.value});
	} else
		alert('<?php echo JText::_( "Fillout required fields" ); ?>');
   	$('indicator2').setStyle('display',id[0]);
   	$('indicator3').setStyle('display',id[1]);
   	return success;
}

function submitbutton(pressbutton) {
	var form = document.adminForm;
		if (document.formvalidator.isValid(form)) {
			if (form.database.value!="") {
				form.submit();
				return true;
			}
		}
		alert('<?php echo JText::_( "Fillout required fields" ); ?>');
		return false;
	}

</script>

