<?php
/**
 * @package JoomAPI
 * @copyright Copyright (c) 2019 - 2019 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

// No direct access to this file.
defined('_JEXEC') or die('Restricted access'); 


class JoomapiModelRequest extends JModelItem
{
  /**
   * Sends the request to the api plugin then returns the plugin's response.
   *
   * @return  array             The plugin's response.
   */
  public function getResponse()
  {
    $jinput = JFactory::getApplication()->input;
    // Get the name of the component.
    $component = $jinput->get('component', '', 'string');

    // Ensure first that a api plugin exists (and is enabled) for this component.
    if(!JPluginHelper::importPlugin('joomapi', $component)) {
      return JoomapiHelperApi::generateError('SRV_PNF');
    }

    if(!JPluginHelper::isEnabled('joomapi', $component)) {
      return JoomapiHelperApi::generateError('SRV_PNE');
    }

    $request = JoomapiHelperApi::getRequest();

    if(isset($request['status'])) {
      return $request;
    }
    //$payload = json_decode($jinput->json->getRaw(), true);
    //$authorization = $jinput->server->get('HTTP_AUTHORIZATION', '', 'str');
    //$authType = $jinput->server->get('HTTP_AUTH_TYPE', '', 'str');

    $dispatcher = JEventDispatcher::getInstance();

    // Sends the request to the api plugin then gets the plugin's response.
    $result = $dispatcher->trigger('onRequest'.ucfirst($component), array($request));

    return $result[0];
  }
}

