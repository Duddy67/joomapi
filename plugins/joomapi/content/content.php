<?php
/**
 * @package JoomAPI
 * @copyright Copyright (c) 2019 - 2019 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

JLoader::register('JoomapiHelperApi', JPATH_SITE.'/components/com_joomapi/helpers/api.php');


class plgJoomapiContent extends JPlugin
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


  public function createArticle($category = null, $catid = null)
  {
  }


  public function getArticles($request)
  {
    $response = array();

    // Retrieves possible extra variables.
    $jinput = JFactory::getApplication()->input;
    $search = $jinput->get('search', '', 'string');
    $page = $jinput->get('page', 0, 'integer');

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
	    $query->select('c.*, ca.title AS cat_title, uc.name AS creator_name, um.name AS modif_name')
		  ->from('#__content AS c')
		  ->join('LEFT', '#__categories AS ca ON c.catid=ca.id')
		  ->join('LEFT', '#__users AS uc ON c.created_by=uc.id')
		  ->join('LEFT', '#__users AS um ON c.modified_by=um.id');

    if($request['id'] !== null) {
      $query->where('c.id='.(int)$request['id']);
    }

    if($request['association'] == 'categories' && $request['a_id'] !== null) {
      $query->where('catid='.(int)$request['a_id']);
    }

    if(!empty($search)) {
      $search = $db->Quote('%'.$db->escape($search, true).'%');
      $query->where('(c.title LIKE '.$search.')');
    }

    $db->setQuery($query);
    $articles = $db->loadAssocList();

    if($request['id'] !== null && empty($articles)) {
      return JoomapiHelperApi::generateError('REQ_RNF');
    }

    $response['status'] = '200 OK';

    if($request['id'] === null) {
      $response['total'] = count($articles);
    }

    $response['articles'] = array();

    foreach($articles as $article) {
      $article['metadata'] = json_decode($article['metadata']);
      $article['images'] = json_decode($article['images']);
      $article['urls'] = json_decode($article['urls']);
      $article['intro_raw'] = strip_tags($article['introtext']);

      $response['articles'][] = $article;
    }

    if($request['id'] !== null) {
      foreach($response['articles'][0] as $key => $value) {
	$response[$key] = $value;
      }

      unset($response['articles']);
    }

    return $response;
  }


  public function createCategory()
  {
  }


  public function getCategories($request)
  {
    $response = array();

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
	    $query->select('*')
	    ->from('#__categories')
	    ->where('extension="com_content"');

    if($request['id'] !== null) {
      $query->where('id='.(int)$request['id']);
    }

    $db->setQuery($query);
    $categories = $db->loadAssocList();

    if($request['id'] !== null && empty($categories)) {
      return JoomapiHelperApi::generateError('REQ_RNF');
    }

    $response['status'] = '200 OK';

    if($request['id'] === null) {
      $response['total'] = count($categories);
    }

    $response['categories'] = array();

    foreach($categories as $category) {
      $category['metadata'] = json_decode($category['metadata']);

      $response['categories'][] = $category;
    }

    if($request['id'] !== null) {
      foreach($response['categories'][0] as $key => $value) {
	$response[$key] = $value;
      }

      unset($response['categories']);
    }

    return $response;
  }


  public function onRequestContent($request)
  {
    switch($request['resource']) {
      case 'articles':
	if($request['action'] == 'read') {
	  return $this->getArticles($request);
	}
      break;

      case 'categories':
	if($request['action'] == 'read') {
	  return $this->getCategories($request);
	}
      break;
    }

    return JoomapiHelperApi::generateError('REQ_IRQ');
  }
}

