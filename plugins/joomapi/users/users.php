<?php
/**
 * @package JoomAPI
 * @copyright Copyright (c) 2019 - 2019 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

JLoader::register('JoomapiHelperApi', JPATH_SITE.'/components/com_joomapi/helpers/api.php');
use Joomla\CMS\User\UserHelper;


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
    $deviceToken = JoomapiHelperApi::getPayload('device_token');

    if(isset($deviceToken['status'])) {
      return $deviceToken;
    }

    $jinput = JFactory::getApplication()->input;
    $authorization = $jinput->server->get('HTTP_AUTHORIZATION', '', 'str');
//file_put_contents('debog_file.txt', print_r($deviceToken, true));
    if(preg_match('#^\s*(Basic)\s+(.+)$#', $authorization, $matches)) {
      $base64 = $matches[2];
      $authValue = base64_decode($base64);
      preg_match('#^(.+):(.+)?$#', $authValue, $matches);
    }

    $db = JFactory::getDbo();
    $query = $db->getQuery(true)
    ->select('id, password')
    ->from('#__users')
    ->where('username='.$db->quote($matches[1]));
    $db->setQuery($query);
    $user = $db->loadObject();

    if($user === null) {
      return JoomapiHelperApi::generateError('SRV_UNF');
    }

    //$userId = UserHelper::getUserId($matches[1]);
    if(UserHelper::verifyPassword($matches[2], $user->password) !== true) {
      return JoomapiHelperApi::generateError('SRV_PNC');
    }

    $response['status'] = '200 OK';

    $response['token'] = $this->generateToken();
    $response['authorization'] = $user->id;

    return $response;
  }


  public function getUsers($request)
  {
  }


  public function generateToken()
  {
    $token = openssl_random_pseudo_bytes(16);
    $token = bin2hex($token);

    return $token;
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

