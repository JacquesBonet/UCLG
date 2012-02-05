<?php

// no direct access
defined('_JEXEC') or die('Restricted access');
$fields = & $this->fields;

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
	<div class="m" style="font-size: larger; min-height: 180px; overflow: visible;"  class="form-validate">
	<form name="adminForm" action="index.php" method="post">
		<input type="hidden" name="table" value="<?php echo $this->dbtable; ?>"  />
		<input type="hidden" name="name" value="<?php echo $this->dbname; ?>"  />
		<input type="hidden" name="server" value="<?php echo JREquest:: getVar("server");?>" />
		<input type="hidden" name="user" value="<?php echo JREquest:: getVar("user");?>" />
		<input type="hidden" name="pass" value="<?php echo JREquest:: getVar("pass");?>" />
		<input type="hidden" name="database" value="<?php echo JREquest:: getVar("database");?>" />
		<input type="hidden" name="option" value="com_joodb" />
		<input type="hidden" name="view" value="joodbentry" />
		<input type="hidden" name="tmpl" value="component" />
		<input type="hidden" name="layout" value="step3" />
		<input type="hidden" name="task" value="addnew" />
		<table cellpadding="5"><tr><td>
		<?php echo JText::_( "Primary Index" ); ?>
		</td><td>
		<select name="fid" style="width: 250px"  class="inputbox required"  >
		<?php
			$fselect = JooDBAdminHelper::selectFieldTypes("primary",$fields);
			foreach ($fselect as $fname) {
				echo "<option>".$fname."</option>";
			}
		 ?>
		</select>
		<?php
			if (count($fselect)<1)
				echo '<div style="color: #d40000; font-weight: bold; font-size:10px;">'.JText::_( "No Primary Index" ).'</div>';
		?>
		</td></tr><tr><td>
		<?php echo JText::_( "Title or Headline" ); ?>
		</td><td>
		<select name="ftitle" style="width: 250px" class="inputbox required"  >
		<?php
			$fselect = JooDBAdminHelper::selectFieldTypes("shorttext",$fields);
			foreach ($fselect as $fname) {
				echo "<option>".$fname."</option>";
			}
		 ?>
		</select>
		<?php
			if (count($fselect)<1)
				echo '<div style="color: #d40000; font-weight: bold; font-size:10px;">'.JText::_( "No Text Field" ).'</div>';
		?>
		</td></tr><tr><td>
		<?php echo JText::_( "Main Content" ); ?>
		</td><td>
		<select name="fcontent" style="width: 250px" class="inputbox required" >
		<?php
			$fselect = JooDBAdminHelper::selectFieldTypes("shorttext",$fields);
			foreach ($fselect as $fname) {
				echo "<option>".$fname."</option>";
			}
		 ?>
		</select>
		<?php
			if (count($fselect)<1)
				echo '<div style="color: #d40000; font-weight: bold; font-size:10px;">'.JText::_( "No Text Field" ).'</div>';
		?>
		</td></tr><tr><td>
		<?php echo JText::_( "Abstract" ); ?>
		</td><td>
		<select name="fabstract" style="width: 250px" >
		 <option value="">...</option>
		<?php
			foreach ($fselect as $fname) {
				echo "<option>".$fname."</option>";
			}
		 ?>
		</select>
		</td></tr><tr><td>
		<?php echo JText::_( "Main Date" ); ?>
		</td><td>
		<select name="fdate" style="width: 250px" >
		 <option value="">...</option>
		<?php
			$fselect = JooDBAdminHelper::selectFieldTypes("date",$fields);
			foreach ($fselect as $fname) {
				echo "<option>".$fname."</option>";
			}
		 ?>
		</select>
		</td></tr></table>
	</form>
	<br/>
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
		if (!document.formvalidator.isValid(form)) {
			alert('<?php echo JText::_('ERROR DEFINE FIELDS'); ?>');
			return false;
		}

		form.submit();
	}
</script>

