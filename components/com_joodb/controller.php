<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * JooDatabase Component Controller
 */
class JoodbController extends JController
{
	/**
	 * Method to show a view
	 */
	function display()
	{
		// Set a default view if none exists
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'catalog' );
		}
		parent::display();
	}

	/**
	 * Submit Form Data. send email and insert to database
	 */
	function submit()
	{
		$application = JFactory::getApplication();

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$document = & JFactory::getDocument();
		$db	=& JFactory::getDBO();
		$params	= & $application->getParams();
		// read database configuration from joobase table
		$model= & $this->getModel('form');
		$jb = & $model->getJoobase();
		// merge the component with the joodb parameters
		$jparams = new JParameter( $jb->params );
		$params->merge($jparams);

		// check captcha if any in form template
		if (strpos($jb->tpl_form,"{joodb captcha")!==false) {
	    	$session =& JFactory::getSession();
			if (!$session->get('joocaptcha') || $session->get('joocaptcha')!=JRequest::getVar('joocaptcha')) {
				$this->setError(JText::_('Captcha code invalid'));
				JRequest::setVar('joocaptcha','');
				JError::raiseWarning( 0, $this->getError() );
				$this->display();
				return;
			}
		}

		// insert form data
		// TODO: Propper Error Handling
		$item=array();
		$msg = JoodbHelper::saveData($jb->table,$item);

		// Attach and resize uploaded image
		$newimage = JRequest::getVar('joodb_dataset_image', null, 'files', 'array' );
		if ($newimage['name']!="") {
			$id = $db->insertid();

			// Make sure that file uploads are enabled in php
			if (!(bool) ini_get('file_uploads')) {
				JError::raise("500", JText::_('WARNINSTALLFILE'));
				return false;
			}
			$destination = JPATH_ROOT.DS."images".DS."joodb".DS."db".$jb->id.DS."img".$id;
			$newimage['name'] = strtolower(JFilterOutput::cleanText($newimage['name']));
			$org_img = $destination."-original".strrchr($newimage['name'],".");
			// Move uploaded image
			jimport('joomla.filesystem.file');
			JFile::upload($newimage['tmp_name'], $org_img);
			if (file_exists($org_img)) {
				// make shure we accept only png, gif or jpg
				$ext = false;
				if ($imageinfo = getimagesize($org_img)) {
				   switch ($imageinfo[2]) {
   					case 1:
   					$ext = ".gif";
					break;
   					case 2:
   					$ext = ".jpg";
					break;
   					case 3:
   					$ext = ".png";
					break;
				   }
				}
				if ($ext!==false) {
			    	chmod($org_img, 0664);
	   			    // normal image
				    JooDBAdminHelper::resizeImage($org_img,$destination.".jpg",$params->get("img_width",480),$params->get("img_height",600));
   				    // thumbnail image
				    JooDBAdminHelper::resizeImage($org_img,$destination."-thumb".$ext,$params->get("thumb_width",120),$params->get("thumb_height",200));
				} else $msg .= " - ".JText::_('Invalid Fileformat');

			}
	     }

		// send formdata to admin
		if ($params->get("infomail",0)==1) {
			$db->setQuery("SELECT email FROM `#__users` WHERE `id` ='".$params->get("infomail_user")."' LIMIT 1");
			if ($email = $db->loadResult()) {
				$MailFrom 	= $application->getCfg('mailfrom');
				$FromName 	= $application->getCfg('fromname');
				$subject = "JooDatabase - ".JText::_('New Database entry')." - ".$jb->name;
				$body = $subject."\r\n";
				$body .= "Site: ".$application->getCfg('sitename')." - ".JUri::Current()."\r\n\r\n";
				$body .= JText::_('Recieved values')."\r\n===================\r\n";
				foreach ($item as $var=>$val) {
					$body .= ucfirst($var).": ".$val."\r\n";;
				}
				$body .= "===================\r\n".JText::_('Statusmessage').": ".$msg."\r\n\r\n";
				$mail = JFactory::getMailer();
				$mail->addRecipient( $email );
				$mail->setSender( array( $MailFrom, $FromName ) );
				$mail->setSubject( $FromName.': '.$subject );
				$mail->setBody( $body );

				$sent = $mail->Send();
			}
		}

		$link = (($params->get("redirect")==1) && ($params->get("redirect_to")!="")) ?
			'index.php?option=com_joodb&Itemid='.$params->get("redirect_to") : 'index.php?option=com_joodb&view=form&Itemid='.JRequest::getInt('Itemid');
		$this->setRedirect(JRoute::_($link,false), $msg);
	}


	/**
	 * Print out a captcha image
	 */
	function captcha()
	{
		JoodbHelper::printCaptcha();
		die();
	}

	/**
	 * Add entries to notepad ...
	 */
	function addToNotepad() {
  		$session =& JFactory::getSession();
		$articles = preg_split("/:/",$session->get('articles',''));
		if ($articles[0]=="") unset($articles[0]);
		$articles[] = JRequest::getCmd("article");
		$session->set('articles', join(":",$articles));
		$this->display();
	}

	/**
	 * Remove entries from notepad
	 */
	function removeFromNotepad() {
  		$session =& JFactory::getSession();
		$articles = preg_split("/:/",$session->get('articles',''));
		if ($articles[0]=="") unset($articles[0]);
		$id = JRequest::getCmd("article");
		foreach ($articles as $ndx => $article)
	    	if ($article==$id) {
	    		unset($articles[$ndx]);
	    	}
		$session->set('articles', join(":",$articles));
		$this->display();
	}

	/**
	 * Delete all entries from notepad
	 */
	function purgeNotepad() {
		$session =& JFactory::getSession();
		$session->set('articles', '');
		$this->display();
	}


}

?>
