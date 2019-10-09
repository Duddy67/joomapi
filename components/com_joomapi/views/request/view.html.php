<?php
/**
 * @package JoomAPI
 * @copyright Copyright (c) 2019 - 2019 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');


/**
 * HTML View class for the JoomAPI component.
 */
class JoomapiViewRequest extends JViewLegacy
{
  protected $response;


  /**
   * Execute and display a template script.
   *
   * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
   *
   * @return  mixed  A string if successful, otherwise an Error object.
   *
   * @see     \JViewLegacy::loadTemplate()
   * @since   3.0
   */
  public function display($tpl = null)
  {
    // Initialise variables
    $this->state = $this->get('State');
    $this->response = $this->get('Response');

    // Check for errors.
    if(count($errors = $this->get('Errors'))) {
      JError::raiseWarning(500, implode("\n", $errors));
      return false;
    }

    // Use the correct json mime-type
    header('Content-Type: application/json');

    // Change the suggested filename
    header('Content-Disposition: attachment;filename="response.json"');
    //
    header('HTTP/1.0 '.$this->response['status']);
    //
    unset($this->response['status']);

    // Enable CORS
    /*if ($enable_cors != '0')
    {
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS');
      header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
    }*/

    echo json_encode($this->response);

    JFactory::getApplication()->close();
  }
}

