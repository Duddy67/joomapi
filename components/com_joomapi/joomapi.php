<?php
/**
 * @package JoomAPI
 * @copyright Copyright (c) 2017 - 2019 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

// No direct access.
defined('_JEXEC') or die('Restricted access'); 

// Registers the component helper files. They will be loaded automatically later as soon
// as an helper class is instantiate.
//JLoader::register('JoomapiHelperRoute', JPATH_SITE.'/components/com_joomapi/helpers/route.php');
JLoader::register('JoomapiHelperApi', JPATH_SITE.'/components/com_joomapi/helpers/api.php');


$controller = JControllerLegacy::getInstance('Joomapi');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();


