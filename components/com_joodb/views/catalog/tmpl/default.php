<?php // no direct acces
defined('_JEXEC') or die('Restricted access'); ?>
<div class="item-page<?php echo $this->params->get('pageclass_sfx')?>">
<?php if ($this->params->get('show_page_title')) : ?>
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx')?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php endif; ?>
<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>
<?php if ( $this->params->get( 'show_description' ) ) : ?>
<table width="100%" class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<tr>
	<td valign="top">
	<?php if ($this->params->get('image')!="-1") : ?>
		<img src="<?php echo $this->baseurl . '/images/'. $this->params->get('image');?>" align="<?php echo $this->params->get('image_align');?>" hspace="6" alt="<?php echo $this->params->get('image');?>" />
	<?php endif; ?>
		<?php echo nl2br($this->params->get('description')); ?>
	</td>
</tr>
</table>
<?php endif; ?>
<form name="searchForm" id="searchForm"  method="get" action="<?php echo JURI::current(); ?>"  >
<input type="hidden" name="option" value="com_joodb"/>
<input type="hidden" name="view" value="catalog"/>
<input type="hidden" name="format" value="html"/>
<input type="hidden" name="reset" value="false"/>
<input type="hidden" name="ordering" value="<?php echo JRequest::getVar("ordering"); ?>"/>
<input type="hidden" name="orderby" value="<?php echo JRequest::getVar("orderby"); ?>"/>
<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar("Itemid"); ?>"/>
<input type="hidden" name="task" value=""/>
<?php

	// replace nodata wildcard if data is empty
	if (empty($this->items)) {
		$this->joobase->tpl_list = str_replace("{joodb nodata}" , JText::_('No data found') ,$this->joobase->tpl_list);
	}


	$pageparts = preg_split("!{joodb loop}!", $this->joobase->tpl_list);
	if (count($pageparts)<3)
		JError::raiseError(500, "Error in catalog template. Remember 2 loop declarations must be found inside catalog template!");

	// get header text
	 $parts = JoodbHelper::splitTemplate($pageparts[0]);
	$page = new JObject();
	 $page->text = $this->parseTemplate($parts);

	 // do the loop
	 if ($this->items) {
	  	// get the parts
	  	$parts = JoodbHelper::splitTemplate($pageparts[1]);
	  	$n=0;
	 	foreach ( $this->items as $item ) {
			$item->loopclass = ($n%2) ? "odd" : "even";
			$page->text .= JoodbHelper::parseTemplate($this->joobase,$parts,$item,$this->params,$this->params);
			$n++;
		}
	 }

	 // get footer text
	 $parts = JoodbHelper::splitTemplate($pageparts[2]);
	 $page->text .= $this->parseTemplate($parts);
 	 // render output text
	 JoodbHelper::printOutput($page,$this->params);


?>
</form>
</div>
<script type="text/javascript" >

	// Submit search form
	function submitSearch(task) {
		form = document.searchForm;
		form.format.value="html";
		if (task=="reset") {
			if (form.search) form.search.value="";
			form.ordering.value="";
			form.orderby.value="";
			$$("#searchForm select.groupselect").each(function(el) {
				for (var i=0; i<el.options.length; i++) el.options[i].selected = false;
				el.selectedIndex = -1; el.value = null;
			});
			$$("#searchForm input.check").each(function(el){el.checked=false; });
			form.reset.value = true
		} else if (task=="xportxls") {
			form.format.value="xls";
		} else if (task=="uncheck") {
			$$("#searchForm input.check").each(function(el){el.checked=false; });
		} else if (task=="setlimit") {
		}
		if (form.search && form.search.value=="<?php echo JText::_('search...'); ?>")
			form.search.value="";
		form.submit();
	}

	// initialize some form elements
	window.addEvent('domready', function() {
		$$("#searchForm select.groupselect").each(function(el){
			if (el.multiple==true) {
				// todo: functional multiple select
//				for (var i=0; i<el.options.length; i++) { el.options[i].wasSelected = el.options[i].selected }
//				el.addEvent('change',function(){
//					el.options[this.selectedIndex].wasSelected = (el.options[this.selectedIndex].wasSelected==true) ? false : true;
//					for (var i=0; i<this.options.length; i++) {this.options[i].selected = this.options[i].wasSelected}
//				});
			} else {
				el.addEvent('change',function(){ submitSearch(); });
			}
		});
		if ($('limit')) {
			$('limit').onchange = function(){ submitSearch('setlimit'); };
		}
	});

</script>