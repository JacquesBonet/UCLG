<?php
/**
 * @package     Falang Driver
 * @subpackage  Add Falang Driver
 * @license     GNU General Public License Version 2, or later http://www.gnu.org/licenses/gpl.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

/**
 * Falang Driver Plugin
 */
class plgSystemFalangdriver extends JPlugin
{

    public function __construct(& $subject, $config = array())
    {
        parent::__construct($subject, $config);
	    $this->loadLanguage();

        //sbou TODO it seem that the constructor is called various times
        //activate driver query trap on site only
        $app = JFactory::getApplication();
        if ($app->isSite() && $this->isFalangDriverActive()) {
            $db = & JFactory::getDbo();
            $db->setSite(true);
        } else {
        }
    }

    /**
     * System Event: onAfterInitialise
     *
     * @return	string
     */
    function onAfterInitialise()
    {
    }

    function onContentPrepareForm($form, $data)
    {
            // Check we are manipulating a valid form.
            if (!in_array($form->getName(), array('com_config.application')))
            {
                return true;
            }

            // Add the profile fields to the form.
            JForm::addFormPath(dirname(__FILE__));
            $result = $form->loadFile('database', false);
    }

    /**
     * Rollback driver on publish and unpublish driver
     * sbou TODO automatique driver selection
     * sbou TODO add mysql driver support
     * @param <type> $context
     * @param <type> $pks
     * @param <type> $value
     */
    function  onContentChangeState($context,$pks,$value)
    {
        if ($context == 'com_plugins.plugin') {
            $ipks = implode( ',', $pks );
            $db = JFactory::getDbo();

            $query = 'SELECT name FROM #__extensions WHERE extension_id in ('.$ipks.') AND element="falangdriver" AND type = "plugin"' ;

            $db->setQuery( $query );
            if (!$db->query()) {
                JError::raiseError(500, $db->getErrorMsg() );
            }

            $config = JFactory::getConfig();
            if ($config->get('dbtype') == 'mysqlix') {
                $config->set('dbtype','mysqli');
                $this->writeConfigFile($config);
            }
            if ($config->get('dbtype') == 'mysqlx') {
                $config->set('dbtype','mysql');
                $this->writeConfigFile($config);
            }

        }
    }

    function writeConfigFile($config) {
        /*
         * Write the configuration file.
         */
        jimport('joomla.filesystem.path');
        jimport('joomla.filesystem.file');

        // Set the configuration file path.
        $file = JPATH_CONFIGURATION . '/configuration.php';

        // Attempt to write the configuration file as a PHP class named JConfig.
        if (!JFile::write($file, $config->toString('PHP', array('class' => 'JConfig', 'closingtag' => false)))) {
                $this->setError(JText::_('PLG_FALANG_DRIVER_CONFIG_ERROR_WRITE_FAILED'));
                return false;
        }

    }

    public function isFalangDriverActive() {
        $db = JFactory::getDBO();
        if (!is_a($db,"JDatabaseMySQLix") && !is_a($db,"JDatabaseMySQLx") ){
           return false;
        }
           return true;
    }

    
}