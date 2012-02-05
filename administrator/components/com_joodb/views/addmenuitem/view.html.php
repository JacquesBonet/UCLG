<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*/

// no direct access
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

// edit or add a custom entry from a table
class JoodbViewAddMenuItem extends JView {

	function display($tpl = null) {
		$db			=& JFactory::getDBO();

		$cid	= JRequest::getVar( 'cid', array(), '', 'array' );
		$targetmenu	= JRequest::getVar( 'targetmenu');
		JArrayHelper::toInteger( $cid );
		$id = $cid[0];
		$jb =& JTable::getInstance( 'joodb', 'Table' );
		$jb->load( $id );

		if (!$targetmenu) { // display menu selector
		$query = 'SELECT menutype AS value, title AS text FROM `#__menu_types`';
		$menu[] = JHTML::_('select.option',  '0', '- '. JText::_( 'Select Menu' ) .' -' );
		$db->setQuery( $query );
		$menu = array_merge( $menu, $db->loadObjectList() );
		$menuselect	= JHTML::_('select.genericlist',  $menu, 'targetmenu', 'class="inputbox" size="1"' , 'value', 'text', $targetmenu);

		?>
		<form action="index.php" method="post" name="adminForm">
			<fieldset>
				<legend><?php echo JText::_( 'Choose menu' ); ?> <b><?php echo $jb->name; ?></b></legend>
				<div style="float:left">
						<?php echo $menuselect; ?>
					<input type="submit" value="<?php echo JText::_( 'Go' ); ?>" class="button" />
					<input type="hidden" name="option" value="com_joodb" />
					<input type="hidden" name="cid" value="<?php echo $id; ?>" />
					<input type="hidden" name="view" value="AddmenuItem" />
					<input type="hidden" name="tmpl" value="component" />
				</div>
			</fieldset>
		</form>
	<?php } else { // add menu item

			$version = new JVersion();
			$item =& JTable::getInstance('menu');
			$item->menutype = $targetmenu;
			if ($version->RELEASE=="1.5") {
				$item->name = ucfirst($jb->name);
				$item->parent = 0;
				$item->ordering = 666;
				$item->params = "joobase=".$jb->id."\nlink_titles=1\norderby=ftitle\nordering=ASC\nlimit=10\n";
				$db->setQuery( "SELECT id FROM #__components WHERE link = 'option=com_joodb'",0,1 );
				$item->componentid = $db->loadResult();
			} else {
				$item->id=0;
				$item->title = ucfirst($jb->name);
				$item->access = 1;
				$item->client_id = 0;
				$item->language = "*";
				$item->setLocation(1, 'last-child');
				$db->setQuery( "SELECT extension_id FROM #__extensions WHERE  `element` =  'com_joodb'",0,1 );
				$item->component_id = $db->loadResult();
				// Decode params
				$pr = new JRegistry;
				$pr->set('joobase', $jb->id);
				$pr->set('limit', '5');
				$item->params =  $pr->toString('json');
			}
			$item->alias = JFilterOutput::stringURLSafe($jb->name);
			$item->link = "index.php?option=com_joodb&view=catalog";
			$item->published = 1;
			$item->type = "component";
			if (!$item->check()) echo $item->getError();
			if (!$item->store(true)) echo $item->getError();
			// Rebuild the tree path.
//			if (!$item->rebuildPath($item->id)) die($item->getError());
			?>
			<fieldset>
				<legend><?php echo JText::_( 'Entry created' ); ?></legend>
				<div style="float:left"><?php echo JText::_( 'New entry created' ); ?></div>
			</fieldset>
		<?php
		}
	}

}