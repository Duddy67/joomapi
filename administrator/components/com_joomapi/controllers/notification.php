<?php
/**
 * @package JoomAPI
 * @copyright Copyright (c) 2017 - 2019 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

// No direct access to this file.
defined('_JEXEC') or die('Restricted access'); 
 

class JoomapiControllerNotification extends JControllerForm
{

  /**
   * Method to save a record.
   *
   * @param   string  $key     The name of the primary key of the URL variable.
   * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
   *
   * @return  boolean  True if successful, false otherwise.
   *
   * @since   1.6
   */
  public function save($key = null, $urlVar = null)
  {
    // Get the jform data.
    //$data = $this->input->post->get('jform', array(), 'array');

    // Gets the current date and time (UTC).
    //$now = JFactory::getDate()->toSql();

    // Saves the modified jform data array 
    //$this->input->post->set('jform', $data);

    // Hand over to the parent function.
    return parent::save($key, $urlVar);
  }


  /**
   * Method to check if you can edit an existing record.
   *
   * Extended classes can override this if necessary.
   *
   * @param   array   $data  An array of input data.
   * @param   string  $key   The name of the key for the primary key; default is id.
   *
   * @return  boolean
   *
   * @since   1.6
   */
  protected function allowEdit($data = array(), $key = 'id')
  {
    $itemId = $data['id'];
    $user = JFactory::getUser();

    // Get the item owner id.
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('created_by')
	  ->from('#__joomapi_notification')
	  ->where('id='.(int)$itemId);
    $db->setQuery($query);
    $createdBy = $db->loadResult();

    $canEdit = $user->authorise('core.edit', 'com_joomapi');
    $canEditOwn = $user->authorise('core.edit.own', 'com_joomapi') && $createdBy == $user->id;

    // Allow edition. 
    if($canEdit || $canEditOwn) {
      return 1;
    }

    // Hand over to the parent function.
    return parent::allowEdit($data, $key);
  }


  /**
   * Method to run batch operations.
   *
   * @param   object  $model  The model.
   *
   * @return  boolean   True if successful, false otherwise and internal error is set.
   *
   * @since   2.5
   */
  public function batch($model = null)
  {
    $this->checkToken();

    // Set the model
    $model = $this->getModel('Notification');

    // Preset the redirect
    $this->setRedirect(JRoute::_('index.php?option=com_joomapi&view=notifications'.$this->getRedirectToListAppend(), false));

    return parent::batch($model);
  }
}

