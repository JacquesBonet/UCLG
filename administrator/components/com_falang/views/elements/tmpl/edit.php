<?php
/**
 * Joom!Fish - Multi Lingual extention and translation manager for Joomla!
 * Copyright (C) 2003 - 2011, Think Network GmbH, Munich
 *
 * All rights reserved.  The Joom!Fish project is a set of extentions for
 * the content management system Joomla!. It enables Joomla!
 * to manage multi lingual sites especially in all dynamic information
 * which are stored in the database.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 * $Id: edit.php 1551 2011-03-24 13:03:07Z akede $
 * @package joomfish
 * @subpackage Views
 *
*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

	global  $act, $task, $option;
	$user = JFactory::getUser();
	$db = JFactory::getDBO();
	//$this->_JoomlaHeader( JText::_('Content elements'), 'extensions', '', false );
	$contentElement = $this->falangManager->getContentElement( $this->id );
?>
<?php if ($this->showMessage) : ?>
<?php echo $this->loadTemplate('message'); ?>
<?php endif; ?>
<form action="index.php" method="post" name="adminForm">
<?php
	jimport('joomla.html.pane');
	$tabs =  JPane::getInstance('tabs');
	echo $tabs->startPane("contentelements");
	echo $tabs->startPanel(JText::_('COM_FALANG_ELEMENTS_CONFIGURATION'),"ElementConfig-page");
	?>
  <table class="adminList" cellspacing="1">
    <tr>
      <th colspan="3"><?php echo JText::_('COM_FALANG_ELEMENTS_GENERAL_INFROMATION');?></th>
    </tr>
    <tr align="center" valign="middle">
      <td width="30%" align="left" valign="top"><strong><?php echo JText::_('COM_FALANG_ELEMENTS_TITLE_NAME');?></strong></td>
      <td width="20%" align="left" valign="top"><?php echo $contentElement->Name;?></td>
		  <td align="left"></td>
    </tr>
    <tr align="center" valign="middle">
      <td width="30%" align="left" valign="top"><strong><?php echo JText::_('COM_FALANG_ELEMENTS_TITLE_AUTHOR');?></strong></td>
      <td width="20%" align="left" valign="top"><?php echo $contentElement->Author;?></td>
		  <td align="left"></td>
    </tr>
    <tr align="center" valign="middle">
      <td width="30%" align="left" valign="top"><strong><?php echo JText::_('COM_FALANG_ELEMENTS_TITLE_VERSION');?></strong></td>
      <td width="20%" align="left" valign="top"><?php echo $contentElement->Version;?></td>
		  <td align="left"></td>
    </tr>
    <tr align="center" valign="middle">
      <td width="30%" align="left" valign="top"><strong><?php echo JText::_('COM_FALANG_ELEMENTS_TITLE_DESCRIPTION');?></strong></td>
      <td width="20%" align="left" valign="top"><?php echo $contentElement->Description;?></td>
		  <td align="left"></td>
    </tr>
  </table>
  	<?php
  	echo $tabs->endPanel();
  	echo $tabs->startPanel(JText::_('COM_FALANG_ELEMENT_DB_REFERENCE'),"ElementReference-page");

  	$contentTable = $contentElement->getTable();
	?>
  <table class="adminList" cellspacing="1">
    <tr>
      <th colspan="2"><?php echo JText::_('COM_FALANG_ELEMENT_DATABASE_INFORMATION');?></th>
    </tr>
    <tr align="center" valign="middle">
      <td width="15%" align="left" valign="top"><strong><?php echo JText::_('COM_FALANG_ELEMENT_DATABASE_TABLE');?></strong><br /><?php echo JText::_('COM_FALANG_ELEMENT_DATABASE_TABLE_HELP');?></td>
      <td width="60%" align="left" valign="top"><?php echo $contentTable->Name;?></td>
    </tr>
    <tr align="center" valign="middle">
      <td width="15%" align="left" valign="top"><strong><?php echo JText::_('COM_FALANG_ELEMENT_DATABASE_FIELDS');?></strong><br /><?php echo JText::_('COM_FALANG_ELEMENT_DATABASE_FIELDS_HELP');?></td>
      <td width="60%" align="left" valign="top">
		  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
				<th><?php echo JText::_('COM_FALANG_DBFIELDNAME');?></th>
				<th><?php echo JText::_('COM_FALANG_DBFIELDTYPE');?></th>
				<th><?php echo JText::_('COM_FALANG_DBFIELDLABEL');?></th>
				<th><?php echo JText::_('COM_FALANG_TRANSLATE');?></th>
			</tr>
			<?php
			$k=0;
			foreach( $contentTable->Fields as $tableField ) {
				?>
		  <tr class="<?php echo "row$k"; ?>">
				<td><?php echo $tableField->Name ? $tableField->Name : "&nbsp;";?></td>
				<td><?php echo $tableField->Type ? $tableField->Type : "&nbsp;";?></td>
				<td><?php echo $tableField->Lable ? $tableField->Lable : "&nbsp;";?></td>
				<td><?php echo $tableField->Translate ? JText::_('YES') : JText::_('NO');?></td>
			</tr>
				<?php
				$k=1-$k;
			}
			?>
			</table>
			<?php
			?>
			</td>
    </tr>
  </table>
  	<?php
  	echo $tabs->endPanel();
  	echo $tabs->startPanel(JText::_('COM_FALANG_ELEMENT_SAMPLE_DATA'),"ElementSamples-page");
  	$contentTable = $contentElement->getTable();
	?>
  <table class="adminList" cellspacing="1">
    <tr>
      <th><?php echo JText::_('Sample data');?></th>
    </tr>
    <tr align="center" valign="middle">
      <td width="100%" align="center" valign="top">
		  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
			<?php
			$sqlFields = "";
			foreach( $contentTable->Fields as $tableField ) {
				if( $sqlFields!='' ) $sqlFields .= ',';
				$sqlFields .= '`' .$tableField->Name. '`';
				?>
				<th nowrap><?php echo $tableField->Lable;?></th>
				<?php
			}
			?>
			</tr>
			<?php
			$k=0;
			$idname = $this->falangManager->getPrimaryKey($contentTable->Name);
			$sql = "SELECT $sqlFields"
			. "\nFROM #__" .$contentTable->Name
			. "\nORDER BY $idname limit 0,10";
			$db->setQuery( $sql	);
			$rows = $db->loadObjectList();
			if( $rows != null ) {
				foreach ($rows as $row) {
				?>
				  <tr class="<?php echo "row$k"; ?>">
					<?php
					foreach( $contentTable->Fields as $tableField ) {
						$fieldName = $tableField->Name;
						$fieldValue = $row->$fieldName;
						if( $tableField->Type='htmltext' ) {
							$fieldValue = htmlspecialchars( $fieldValue );
						}

						if( $fieldValue=='' ) $fieldValue="&nbsp;";
						if( strlen($fieldValue) > 97 ) {
							$fieldValue = substr( $fieldValue, 0, 100) . '...';
						}

						?>
						<td valign="top"><?php echo $fieldValue;?></td>
						<?php
					}
					?>
					</tr>
						<?php
						$k=1-$k;
				}
			}
			?>
			</table>
			<?php
			?>
			</td>
    </tr>
  </table>
<?php
echo $tabs->endPanel();
echo $tabs->endPane();
	?>
	<input type="hidden" name="option" value="com_falang" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>