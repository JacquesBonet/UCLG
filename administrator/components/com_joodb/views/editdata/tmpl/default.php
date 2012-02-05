<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

$item = & $this->item;
$jb = & $this->jb;

// 	Load the JEditor object
$editor =& JFactory::getEditor();

?>
<style>
	.invalid { background-color: #F9C4C0; }
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm"  class="form-validate" enctype="multipart/form-data">
	<input type="hidden" name="option" value="com_joodb" />
	<input type="hidden" name="joodbid" value="<?php echo $jb->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="<?php echo $jb->fid; ?>" value="<?php echo $this->id?>" />
<div class="width-60 fltlft">
<!--Stammdaten -->
	<fieldset class="adminform" style="width: auto;">
		<legend><?php echo JText::_( "Editable fields" ); ?></legend>
		<table cellspacing="1" width="100%" class="paramlist admintable">
<?php
	foreach ($jb->fields as $fname=>$fcell) {
		$typearr = preg_split("/\(/",$fcell->Type);
		if (!isset($item->{$fname})) $item->{$fname} = null;
		$typevals = array("");
		$required = ($fcell->Null=="NO") ? "required" :"";
		if (isset($typearr[1])) { $typevals =  preg_split("/','/",trim($typearr[1],"')"));	}
		// get default value
		if (($this->id==0) && ($fcell->Default!=NULL)) { $item->{$fname} = $fcell->Default; }
		echo '<tr><td class="paramlist_key">'.ucfirst($fname).'</td><td class="paramlist_value">';
		    if ($fcell->Extra=='auto_increment') {
				echo '<input class="inputbox" type="text" name="'.$fname.'" value="'.htmlspecialchars($item->{$fname}, ENT_COMPAT, 'UTF-8').'" maxlength="40" size="60" style="width: 200px" disabled />';
		    } else
			switch ($typearr[0]) {
				case 'varchar' :
				case 'char' :
				case 'tinytext' :
					echo '<input class="inputbox '.$required.'" type="text" name="'.$fname.'" value="'.htmlspecialchars($item->{$fname}, ENT_COMPAT, 'UTF-8').'" maxlength="'.$typevals[0].'" size="60" style="width: '.(($typevals[0]<30) ? '300px' : '500px').'" />';
				break;
				case 'int' :
				case 'smallint' :
				case 'mediumint' :
				case 'bigint' :
				case 'decimal' :
				case 'float' :
				case 'double' :
				case 'real' :
					echo '<input class="inputbox '.$required.'" type="text" name="'.$fname.'" value="'.htmlspecialchars($item->{$fname}, ENT_COMPAT, 'UTF-8').'" maxlength="40" size="60" style="width: 200px" />';
				break;
				case 'tinyint' :
					echo '<input class="inputbox '.$required.'" type="text" name="'.$fname.'" value="'.htmlspecialchars($item->{$fname}, ENT_COMPAT, 'UTF-8').'" maxlength="4" size="4" style="width: 50px" />';
				break;
				case 'datetime' :
				case 'timestamp' :
					$item->{$fname} = preg_replace("/[^0-9:\- ]/","",$item->{$fname});
					echo JHTML::_('calendar', $item->{$fname} , $fname, $fname, '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19'));
					break;
				case 'date' :
					$item->{$fname} = preg_replace("/[^0-9\-]/","",$item->{$fname});
					echo JHTML::_('calendar', $item->{$fname} , $fname, $fname, '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'10'));
				break;
				case 'year' :
					echo '<input class="inputbox '.$required.'" type="text" name="'.$fname.'" value="'.((int) $item->{$fname}).'" maxlength="4" size="4" style="width: 50px" />';
				break;
				case 'time' :
					echo '<input class="inputbox '.$required.'" type="text" name="'.$fname.'" value="'.($item->{$fname}).'" maxlength="8" size="4" style="width: 70px" />';
				break;
				case 'text' :
				case 'mediumtext' :
				case 'longtext' :
					echo $editor->display($fname, stripslashes($item->{$fname}), '500', '250', '40', '6',false);
///					echo '<textarea class="'.$required.'" cols="80" rows="6" name="'.$fname.'" style="width: 500px">'.stripslashes($item->{$fname}).'</textarea>';
				break;
				// special handling for enum and set
				case 'enum' :
					echo '<select class="inputbox '.$required.'" type="text" name="'.$fname.'" style="width: 200px" />';
					echo '<option value="" >...</option>';
					foreach ($typevals as $value) {
						echo '<option value="'.$value.'" '.(($value==$item->{$fname}) ? 'selected' : '' ).'>'.$value.'</option>';
					}
					echo '</select>';
				break;
				case 'set' :
					echo '<select class="inputbox '.$required.'" type="text" multiple="multiple" name="'.$fname.'[]" style="width: 200px" />';
					$setarray = preg_split("/,/",$item->{$fname});
					foreach ($typevals as $value) {
						echo '<option value="'.$value.'" '.(in_array($value,$setarray)? 'selected' : '' ).'>'.$value.'</option>';
					}
					echo '</select>';
				break;
				default:
					echo '<input class="inputbox '.$required.'" type="text" name="'.$fname.'" value="'.htmlspecialchars($item->{$fname}, ENT_COMPAT, 'UTF-8').'" maxlength="'.$typevals[0].'" size="60" style="width: 500px;" />';
			}
			echo '</td></tr>';
		}
?>
</table>
</fieldset>
</div>
<div class="width-40 fltrt">
<fieldset class="adminform">
	<legend><?php echo JText::_( "Upload image" ); ?></legend>
<?php
	$imgpath = JPATH_ROOT.DS."images".DS."joodb".DS."db".$jb->id;
	// attach image to dataset
	if (!file_exists($imgpath)) {
		if (!@mkdir($imgpath,0777, true)) {
   			echo '<p style="color:red; font-weight: bold;">Can not create JooDB image directory. Make shure that /images is writable</p>';
		}
	}
?>
<table cellspacing="1" width="100%" class="paramlist admintable">
	<tr>
		<td colspan="2"><input name="dataset_image" type="file" size="30" style="width:200px" maxlength="1000000" accept="*.jpg, *.jpeg, *.png" /></td>
	</tr>
	<tr>
		<td class="paramlist_key"><?php echo JText::_( "Existing image" ); ?></td>
		<td>
<?php
		if (($item->{$jb->fid}) && (file_exists($imgpath.DS."img".$item->{$jb->fid}.".jpg"))) {
			echo '<img  style="border: 1px solid #444; background-color: #ccc; padding: 10px;" src="'.JURI::root(true).'/images/joodb/db'.$jb->id.'/img'.$item->{$jb->fid}.'-thumb.jpg" alt="*" />';
		} else {
			echo JText::_( "None" );;
		}
?>
		</td>
	</tr>
</table>
	</fieldset>
</div>
<?php echo JHTML::_( 'form.token' );?>
</form>
<script language="JavaScript">

//Send Form
if (window.Joomla !== undefined)
	Joomla.submitbutton = function(task) { submitbutton(task);  }

function submitbutton(task) {

			var form = document.adminForm;
			form.task.value = task;
			if (task == 'listdata') {
				if (window.Joomla) { Joomla.submitform(task, form); } else { form.submit(); }
				return true;
			}

			// do field validation
			if (form.<?php echo $jb->ftitle; ?>.value == ""){
				alert('<?php echo JText::_( "Must have title" ); ?>');
				form.<?php echo $jb->ftitle; ?>.focus();
				return false;
			} else  {
		        if (document.formvalidator.isValid(form)) {
		        	if (window.Joomla) { Joomla.submitform(task, form); } else { form.submit(); }
					return true;
	      		  } else {
	                alert('<?php echo JText::_( "Fillout required fields" ); ?>');
	       		 }
			}
	}

</script>
