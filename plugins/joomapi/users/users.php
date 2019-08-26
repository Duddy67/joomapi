<?php
/**
 * @package JoomAPI
 * @copyright Copyright (c) 2019 - 2019 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');



class plgJoomapiUsers extends JPlugin
{

  /**
   * Constructor.
   *
   * @param   object  &$subject  The object to observe
   * @param   array   $config    An optional associative array of configuration settings.
   *
   * @since   3.7.0
   */
  public function __construct(&$subject, $config)
  {
    // Loads the component language.
    $lang = JFactory::getLanguage();
    $langTag = $lang->getTag();
    $lang->load('com_joomapi', JPATH_ROOT.'/administrator/components/com_joomapi', $langTag);

    parent::__construct($subject, $config);
  }


  public function connectUser()
  {
    $jinput = JFactory::getApplication()->input;
    $authorization = $jinput->server->get('HTTP_AUTHORIZATION', '', 'str');

    if(preg_match('#^\s*(Basic)\s+(.+)$#', $authorization, $matches)) {
      $base64 = $matches[2];
      $authValue = base64_decode($base64);
      preg_match('#^(.+):(.+)?$#', $authValue, $matches);
    }

    $response['status'] = '200 OK';

    $response['token'] = 'fgh8dg89fhdg9988';
    $response['authorization'] = $authValue;

    return $response;
  }


  public function getUsers($request)
  {
  }


  public function generateToken()
  {
  }


  public function onRequestUsers($request)
  {
    switch($request['resource']) {
      case 'connect':
	if($request['action'] == 'create') {
	  return $this->connectUser();
	}
      break;

      case 'users':
	if($request['action'] == 'read') {
	  return $this->getUsers($request);
	}
      break;
    }

    return JoomapiHelperApi::generateError('REQ_IRQ');
  }
}

