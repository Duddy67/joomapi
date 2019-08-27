<?php
/**
 * @package JoomAPI
 * @copyright Copyright (c) 2019 - 2019 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');


/**
 * JoomAPI Component API Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_joomapi
 * @since       1.5
 */
class JoomapiHelperApi
{
  /**
   * Builds the request to execute from the query parameters passed in the url.
   *
   * @return  Array             The request in the form of an associative array.
   */
  public static function getRequest()
  {
    // CRUD actions mapping array.
    $actions = array('POST' => 'create', 'GET' => 'read', 'PUT' => 'update', 'DELETE' => 'delete');
    // Prepares the request array.
    $request = array('action' => '', 'resource' => '', 'id' => null, 'association' => null, 'a_id' => null);
    $jinput = JFactory::getApplication()->input;

    // Checks that the given method is handled by the component.
    if(!isset($actions[$jinput->getMethod()])) {
      return self::generateError('REQ_AUN');
    }

    $request['action'] = $actions[$jinput->getMethod()];

//file_put_contents('debog_file.txt', print_r($_REQUEST, true));
    // Collects the parameters of the url query.
    $resource = $jinput->get('resource', '', 'string');
    $id = $jinput->get('id', 0, 'integer');
    $association = $jinput->get('association', '', 'string');
    $aId = $jinput->get('a_id', 0, 'integer');

    // Sets the request array according to the collected parameters.

    if(!empty($association)) {
      $request['association'] = $association;

      if($aId) {
	$request['a_id'] = $aId;
      }
    }

    $request['resource'] = $resource;

    if($id) {
      $request['id'] = $id;
    }

    return $request;
  }


  public static function getPayload($id = '')
  {
    // Gets the payload sent by the client.
    $payload = json_decode(JFactory::getApplication()->input->json->getRaw(), true);

    // Checks that the payload is valid and that it's an array.
    if(is_null($payload) || !is_array($payload)) {
      return self::generateError('REQ_IPL');
    }

    if(!empty($id)) {
      if(isset($payload[$id])) {
	return $payload[$id];
      }
      else {
	return self::generateError('REQ_INF');
      }
    }

    return $payload;
  }


  /**
   * Generates an error array from the given error code.
   *
   * @param   string    $errorCode	The code of the error.
   *
   * @return  array                     The generated error array.
   *
   */
  public static function generateError($errorCode, $errorDetails = '')
  {
    $error = array();

    switch($errorCode) {
      case 'REQ_RNF':
	$error['status'] = '404 Not Found';
        $error['error_code'] = 'REQ_RNF';
        $error['error_description'] = 'Resource not found';
        break;

      case 'SRV_PNF':
	$error['status'] = '503 Service Unavailable';
        $error['error_code'] = 'SRV_PNF';
        $error['error_description'] = 'Plugin not found';
        break;

      case 'SRV_PNE':
	$error['status'] = '503 Service Unavailable';
        $error['error_code'] = 'SRV_PNE';
        $error['error_description'] = 'Plugin not enabled';
        break;

      case 'SRV_UNF':
	$error['status'] = '404 Not Found';
        $error['error_code'] = 'SRV_UNF';
        $error['error_description'] = 'User not found';
        break;

      case 'SRV_PNC':
	$error['status'] = '401 Unauthorized';
        $error['error_code'] = 'SRV_PNC';
        $error['error_description'] = 'Password not correct';
        break;

      case 'REQ_AUN':
	$error['status'] = '400 Bad Request';
        $error['error_code'] = 'REQ_AUN';
        $error['error_description'] = 'Action unknown';
        break;

      case 'REQ_IRQ':
	$error['status'] = '400 Bad Request';
        $error['error_code'] = 'REQ_IRQ';
        $error['error_description'] = 'Invalid request';
        break;

      case 'REQ_IPL':
	$error['status'] = '400 Bad Request';
        $error['error_code'] = 'REQ_IPL';
        $error['error_description'] = 'Invalid payload';
        break;

      case 'REQ_INF':
	$error['status'] = '404 Not Found';
        $error['error_code'] = 'REQ_INF';
        $error['error_description'] = 'Identifier not found';
        break;
    }

    return $error;
  }
}

