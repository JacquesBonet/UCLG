<?php
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * The Configuration Sub-Menu to switch between Tabs.
 */
?>
<div class="submenu-box">
	<div class="submenu-pad">
		<ul id="submenu" class="configuration">
			<li><a id="general" class="active"><?php echo JText::_( 'General options' ); ?></a></li>
			<li><a id="catalog"><?php echo JText::_( 'Catalog template' ); ?></a></li>
			<li><a id="single"><?php echo JText::_( 'Singleview template' ); ?></a></li>
			<li><a id="print"><?php echo JText::_( 'Print template' ); ?></a></li>
			<li><a id="form"><?php echo JText::_( 'Form template' ); ?></a></li>
		</ul>
		<div class="clr"></div>
	</div>
</div>
<div class="clr"></div>