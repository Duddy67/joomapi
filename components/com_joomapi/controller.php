<?php
/**
 * @package JoomAPI
 * @copyright Copyright (c) 2017 - 2019 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

// No direct access.
defined('_JEXEC') or die('Restricted access'); 


class JoomapiController extends JControllerLegacy
{
  /**
   * Constructor.
   *
   * @param   array  $config  An optional associative array of configuration settings.
   * Recognized key values include 'name', 'default_task', 'model_path', and
   * 'view_path' (this list is not meant to be comprehensive).
   *
   * @since   12.2
   */
  public function __construct($config = array())
  {
    // ...

    parent::__construct($config);
  }


  public function display($cachable = false, $urlparams = false) 
  {
    // Set the view, (request by default).
    $vName = $this->input->getCmd('view', 'request');
    $this->input->set('view', $vName);

    // Make sure the parameters passed in the input by the component are safe.
    $safeurlparams = array('catid' => 'INT', 'id' => 'INT',
			    'cid' => 'ARRAY', 'limit' => 'UINT',
			    'limitstart' => 'UINT', 'return' => 'BASE64',
			    'filter' => 'STRING', 'filter-search' => 'STRING',
			    'filter-ordering' => 'STRING', 'lang' => 'CMD',
			    'Itemid' => 'INT');

    // Display the view.
    parent::display($cachable, $safeurlparams);
  }


  /**
   * Checks whether the token is valid before sending the Ajax request to the corresponding Json view.
   *
   * @return  mixed	The Ajax request result or an error message if the token is
   * 			invalid.  
   */
  public function ajax() 
  {
    if(!JSession::checkToken('get')) {
      echo new JResponseJson(null, JText::_('JINVALID_TOKEN'), true);
    }
    else {
      parent::display();
    }
  }
}


