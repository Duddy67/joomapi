<?php
/**
 * @package JoomAPI
 * @copyright Copyright (c) 2017 - 2019 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

// No direct access to this file.
defined('_JEXEC') or die('Restricted access'); 


class JoomapiModelNotification extends JModelAdmin
{
  /**
   * The type alias for this content type.
   *
   * @var    string
   * @since  3.2
   */
  public $typeAlias = 'com_joomapi.notification';

  /**
   * Batch processes supported by NotificationBook (over and above the standard batch processes).
   *
   * @var array
   */
  protected $joomapi_batch_commands = array('user_id' => 'batchUser');

  // Prefix used with the controller messages.
  protected $text_prefix = 'COM_JOOMAPI';


  /**
   * Returns a Table object, always creating it.
   *
   * @param   string  $type    The table type to instantiate
   * @param   string  $prefix  A prefix for the table class name. Optional.
   * @param   array   $config  Configuration array for model. Optional.
   *
   * @return  JTable    A database object
   */
  public function getTable($type = 'Notification', $prefix = 'JoomapiTable', $config = array()) 
  {
    return JTable::getInstance($type, $prefix, $config);
  }


  /**
   * Method to get the record form.
   *
   * @param   array    $data      Data for the form.
   * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
   *
   * @return  JForm|boolean  A JForm object on success, false on failure
   *
   * @since   1.6
   */
  public function getForm($data = array(), $loadData = true) 
  {
    $form = $this->loadForm('com_joomapi.notification', 'notification', array('control' => 'jform', 'load_data' => $loadData));

    if(empty($form)) {
      return false;
    }

    return $form;
  }


  /**
   * Method to get the data that should be injected in the form.
   *
   * @return  mixed  The data for the form.
   *
   * @since   1.6
   */
  protected function loadFormData() 
  {
    // Check the session for previously entered form data.
    $data = JFactory::getApplication()->getUserState('com_joomapi.edit.notification.data', array());

    if(empty($data)) {
      $data = $this->getItem();
    }

    return $data;
  }


  /**
   * Method to get a single record.
   *
   * @param   integer  $pk  The id of the primary key.
   *
   * @return  mixed  Object on success, false on failure.
   */
  public function getItem($pk = null)
  {
    if($item = parent::getItem($pk)) {
      // Gets both intro_text and full_text together as notificationtext
      $item->notificationtext = trim($item->full_text) != '' ? $item->intro_text."<hr id=\"system-readmore\" />".$item->full_text : $item->intro_text;

      // Gets tags for this item.
      if(!empty($item->id)) {
	$item->tags = new JHelperTags;
	$item->tags->getTagIds($item->id, 'com_joomapi.notification');
      }
    }

    return $item;
  }


  /**
   * Prepare and sanitise the table data prior to saving.
   *
   * @param   JTable  $table  A JTable object.
   *
   * @return  void
   *
   * @since   1.6
   */
  protected function prepareTable($table)
  {
    // Set the publish date to now
    if($table->published == 1 && (int)$table->publish_up == 0) {
      $table->publish_up = JFactory::getDate()->toSql();
    }

    if($table->published == 1 && intval($table->publish_down) == 0) {
      $table->publish_down = $this->getDbo()->getNullDate();
    }
  }


  /**
   * Saves the manually set order of records.
   *
   * @param   array    $pks    An array of primary key ids.
   * @param   integer  $order  +1 or -1
   *
   * @return  mixed
   *
   * @since   12.2
   */
  public function saveorder($pks = null, $order = null)
  {

    // Hands over to the parent function.
    return parent::saveorder($pks, $order);
  }


  /**
   * Method to perform batch operations on an item or a set of items.
   *
   * @param   array  $commands  An array of commands to perform.
   * @param   array  $pks       An array of item ids.
   * @param   array  $contexts  An array of item contexts.
   *
   * @return  boolean  Returns true on success, false on failure.
   *
   * @since   1.7
   */
  public function batch($commands, $pks, $contexts)
  {
    // Includes the additional batch processes which the NotificationBook component supports.
    $this->batch_commands = array_merge($this->batch_commands, $this->joomapi_batch_commands);

    return parent::batch($commands, $pks, $contexts);
  }


  /**
   * Batch change a linked user.
   *
   * @param   integer  $value     The new value matching a User ID.
   * @param   array    $pks       An array of row IDs.
   * @param   array    $contexts  An array of item contexts.
   *
   * @return  boolean  True if successful, false otherwise and internal error is set.
   *
   * @since   2.5
   */
  protected function batchUser($value, $pks, $contexts)
  {
    foreach ($pks as $pk)
    {
      if ($this->user->authorise('core.edit', $contexts[$pk]))
      {
	$this->table->reset();
	$this->table->load($pk);
	$this->table->created_by = (int) $value;

	$this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);

	if (!$this->table->store())
	{
	  $this->setError($this->table->getError());

	  return false;
	}
      }
      else
      {
	$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

	return false;
      }
    }

    // Clean the cache
    $this->cleanCache();

    return true;
  }
}

