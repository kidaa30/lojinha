<?php
/**
* @version 2.0
* @package DJ Classifieds
* @subpackage DJ Classifieds Component
* @copyright Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
*
*
* DJ Classifieds is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Classifieds is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Classifieds. If not, see <http://www.gnu.org/licenses/>.
*
*/

defined ('_JEXEC') or die('Restricted access'); 
if(!defined("DS")){ define('DS',DIRECTORY_SEPARATOR);}
require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djseo.php');

class KomentoComDjclassifieds extends KomentoExtension
{
	public $_item;
	public $_map = array(
		'id'			=> 'id',
		'title'			=> 'name',
		'hits'			=> 'hits',
		'created_by'	=> 'created_by',
		'catid'			=> 'cat_id'
		);

	private $_currentTrigger = '';

	public function __construct( $component )
	{
		//$this->addFile( JPATH_ROOT .'administrator'. DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_djclassifieds' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR .'djseo.php' );

		parent::__construct( $component );
	}

	public function load( $cid )
	{
		static $instances = array();

		if( !isset( $instances[$cid] ) )
		{
			$db		= Komento::getDBO();
			$query	= 'SELECT a.*, c.alias AS category_alias'
					. ' FROM ' . $db->nameQuote( '#__djcf_items' ) . ' AS a'
					. ' LEFT JOIN ' . $db->nameQuote( '#__djcf_categories')  . ' AS c ON c.id = a.cat_id'
					. ' WHERE a.id' . '=' . $db->quote($cid);
			$db->setQuery( $query );

			if( !$this->_item = $db->loadObject() )
			{
				return $this->onLoadArticleError( $cid );
			}

			$instances[$cid] = $this->_item;
		}

		$this->_item = $instances[$cid];

		return $this;
	}

	public function getContentIds( $categories = '' )
	{
		$db		= Komento::getDBO();
		$query = '';

		if( empty( $categories ) )
		{
			$query = 'SELECT `id` FROM ' . $db->nameQuote( '#__djcf_items' ) . ' ORDER BY `id`';
		}
		else
		{
			if( is_array( $categories ) )
			{
				$categories = implode( ',', $categories );
			}

			$query = 'SELECT DISTINCT `id` FROM ' . $db->nameQuote( '#__djcf_items' ) . ' WHERE `cat_id` IN (' . $categories . ') ORDER BY `id`';
		}

		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	public function getCategories()
	{
		$db		= Komento::getDBO();
		$query	= 'SELECT a.id, a.name AS title, a.parent_id, a.name, a.parent_id as parent'
				. ' FROM `#__djcf_categories` AS a'
				. ' WHERE a.published = 1'
				. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$categories	= $db->loadObjectList();

		$children = array();

		foreach ($categories as $row)
		{
			$pt		= $row->parent_id;
			$list	= @$children[$pt] ? $children[$pt] : array();
			$list[] = $row;
			$children[$pt] = $list;
		}

		$categories	= JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);

		return $categories;
	}

	public function isListingView()
	{
		return false;
	}

	public function isEntryView()
	{
		return ( $this->_currentTrigger == 'onDJClassifiedsItem' ) ? true : false;
	}

	public function onExecute( &$article, $html, $view, $options = array() )
	{
		if( $options['trigger'] == 'onDJClassifiedsItem' )
		{
			$model	= Komento::getModel( 'comments' );
			$count	= $model->getCount( $this->component, $this->getContentId() );
			$article->numOfComments = $count;

			return $html;
		}
	}


	public function getEventTrigger()
	{
		return array( 'onDJClassifiedsItem');
	}

	public function getContext()
	{
		return array( 'com_djclassifieds.item');
	}

	public function getAuthorName()
	{
		return $this->_item->author ? $this->_item->author : null;
	}

	/*
	public function getCommentAnchorId()
	{
		return '';
	}*/

	public function onBeforeLoad( $eventTrigger, $context, &$article, &$params, &$page, &$options )
	{
		if( !$params instanceof JRegistry )
		{
			return false;
		}

		$this->_currentTrigger = $eventTrigger;

		return true;
	}

	public function onParameterDisabled( $eventTrigger, $context, &$article, &$params, &$page, &$options )
	{
		$params->set( 'comments', 0 );
		return false;
	}

	public function getContentPermalink()
	{
		
		$link = DJClassifiedsSEO::getItemRoute($this->_item->id.':'.urlencode($this->_item->alias), $this->_item->cat_id.':'.urlencode($this->_item->category_alias));
		if( JFactory::getApplication()->isSite() ){
			$link = urldecode(JRoute::_($link));
		}
		$link = $this->prepareLink( $link );
		return $link;
	}
	public function getComponentName() {
		return 'DJ-Classifieds';
	}
	public function getComponentIcon() {
		return JURI::root(true).'/components/com_djclassifieds/assets/images/djcf_icon.png';
	}
}
