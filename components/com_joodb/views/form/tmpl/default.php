<?php // no direct access
	defined('_JEXEC') or die('Restricted access');
?>
<?php if ($this->params->get('show_page_title')) : ?>
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx')?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php endif; ?>
<?php if ($this->params->get('show_page_heading')) : ?>
	<h1 class="<?php echo $this->params->get('pageclass_sfx')?>"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
<?php endif; ?>
<script type="text/javascript">
<!--
	function validateForm() {
		var frm = document.joodbForm;
		var valid = document.formvalidator.isValid(frm);
		if (valid == false) {
			// do field validation
			alert( "<?php echo JText::_( 'Required fields', true ); ?>" );
			return false;
		}
		return true;
	}
// -->
</script>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php if(isset($this->error)) : ?>
<tr>
	<td><?php echo $this->error; ?><br/></td>
</tr>
<?php endif; ?>
<tr>
	<td>
	<form action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="joodbForm" id="joodbForm" class="form-validate" enctype="multipart/form-data">
<?php

 // get header text
 $parts = JoodbHelper::splitTemplate($this->joobase->tpl_form);
 $pagetext = $this->parseTemplate($this->joobase,$parts);

 echo $pagetext;

?>
	<input type="hidden" name="option" value="com_joodb" />
	<input type="hidden" name="view" value="form" />
	<input type="hidden" name="Itemid" value="<?php echo $this->menu->id; ?>" />
	<input type="hidden" name="task" value="submit" />
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<br />
	</td>
</tr>
</table>