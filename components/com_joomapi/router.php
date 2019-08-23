<?php
/**
 * @package JoomAPI
 * @copyright Copyright (c) 2017 - 2019 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Routing class of com_joomapi
 *
 * @since  3.3
 */
class JoomapiRouter extends JComponentRouterBase
{
  /**
   * Build method for URLs
   * This method is meant to transform the query parameters into a more human
   * readable form. It is only executed when SEF mode is switched on.
   *
   * @param   array  &$query  An array of URL arguments
   *
   * @return  array  The URL arguments to use to assemble the subsequent URL.
   *
   * @since   3.3
   */
  public function build(&$query)
  {
    $segments = array();

    if(isset($query['component'])) {
      $segments[] = $query['component'];
      unset($query['component']);
    }

    if(isset($query['association'])) {
      $segments[] = $query['association'];
      unset($query['association']);
    }

    if(isset($query['a_id'])) {
      $segments[] = $query['a_id'];
      unset($query['a_id']);
    }

    if(isset($query['resource'])) {
      $segments[] = $query['resource'];
      unset($query['resource']);
    }

    if(isset($query['id'])) {
      $segments[] = $query['id'];
      unset($query['id']);
    }

    return $segments;
  }


  /**
   * Parse method for URLs
   * This method is meant to transform the human readable URL back into
   * query parameters. It is only executed when SEF mode is switched on.
   *
   * @param   array  &$segments  The segments of the URL to parse.
   *
   * @return  array  The URL attributes to be used by the application.
   *
   * @since   3.3
   */
  public function parse(&$segments)
  {
    $vars = array();

    $count = count($segments);

    if($count == 2) {
      $vars['component'] = $segments[0];
      $vars['resource'] = $segments[1];
    }
    elseif($count == 3) {
      $vars['component'] = $segments[0];
      $vars['resource'] = $segments[1];
      $vars['id'] = $segments[2];
    }
    elseif($count == 4) {
      $vars['component'] = $segments[0];
      $vars['association'] = $segments[1];
      $vars['a_id'] = $segments[2];
      $vars['resource'] = $segments[3];
    }
    elseif($count == 5) {
      $vars['component'] = $segments[0];
      $vars['association'] = $segments[1];
      $vars['a_id'] = $segments[2];
      $vars['resource'] = $segments[3];
      $vars['id'] = $segments[4];
    }

    return $vars;
  }
}


/**
 * Notification router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function JoomapiBuildRoute(&$query)
{
  $app = JFactory::getApplication();
  $router = new JoomapiRouter($app, $app->getMenu());

  return $router->build($query);
}


/**
 * Notification router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function JoomapiParseRoute($segments)
{
  $app = JFactory::getApplication();
  $router = new JoomapiRouter($app, $app->getMenu());

  return $router->parse($segments);
}

