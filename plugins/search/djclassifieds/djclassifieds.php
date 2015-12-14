<?php
/**
* @version 2.0
* @package DJ Classifieds
* @subpackage DJ Classifieds Search Plugin
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
if(!defined("DS")){
	define('DS',DIRECTORY_SEPARATOR);
} 
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djseo.php');
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgSearchDjclassifieds extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	function onContentSearchAreas() {
		static $areas = array(
			'djclassifieds' => 'PLG_SEARCH_DJCLASSIFIEDS_DJCLASSIFIEDSITEMS'
			);
			return $areas;
	}
	
	function onContentSearch( $text, $phrase='', $ordering='', $areas=null )
	{
		$app = JFactory::getApplication();
		$db		= JFactory::getDBO();
		$searchText = $text;
	
		if (is_array( $areas )) {
			if (!array_intersect( $areas, array_keys( $this->onContentSearchAreas() ) )) {
				return array();
			}
		}
	
		// load plugin params info
	 	$plugin = JPluginHelper::getPlugin('search', 'djclassifieds');
	 	$pluginParams = $this->params;
	
		$limit = $pluginParams->def( 'search_limit', 50 );
	
		$text = trim( $text );
		if ( $text == '' ) {
			return array();
		}
		
		$wheres	= array();
		$where = '';
		switch ($phrase)
		{
			case 'exact':
				$text		= $db->Quote('%'.$db->getEscaped($text, true).'%', false);
				$wheres2	= array();
				$wheres2[]	= 'i.name LIKE '.$text;
				$wheres2[]	= 'i.intro_desc LIKE '.$text;
				$wheres2[]	= 'i.description LIKE '.$text;	
				$where		= '(' . implode(') OR (', $wheres2) . ')';				
				break;

			case 'all':
			case 'any':
			default:
				$words	= explode(' ', $text);
				$wheres = array();
				foreach ($words as $word)
				{
					$word		= $db->Quote('%'.$db->getEscaped($word, true).'%', false);
					$wheres2	= array();
					$wheres2[]	= 'i.name LIKE '.$word;
					$wheres2[]	= 'i.intro_desc LIKE '.$word;
					$wheres2[]	= 'i.description LIKE '.$word;	
					$wheres[]	= implode(' OR ', $wheres2);
				}
				$where	= '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}
	
		switch ( $ordering ) {
			case 'alpha':
				$order = 'i.name ASC';
				break;
	
			case 'category':
			case 'popular':
			case 'newest':
			case 'oldest':
			default:
				$order = 'i.name DESC';
		}
		
		$date_time	= JFactory::getDate();
		$date_exp	= $date_time->toSQL();
		$text		= $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
		$query 		= ' SELECT i.id AS id, i.name AS title, i.alias as alias, i.intro_desc AS intro, i.date_start as created, c.id AS cat_id, c.name AS category, c.alias as c_alias, i.description as text,i.metakey, i.metadesc '
					. ' FROM #__djcf_items AS i '
					. ' LEFT JOIN #__djcf_categories AS c ON c.id = i.cat_id '
					. ' WHERE i.date_exp > \''.$date_exp.'\' AND ('.$where.')'
					. ' AND i.published = 1 AND c.published = 1'
					. ' GROUP BY id'
					. ' ORDER BY '. $order
			;
		$db->setQuery( $query, 0, $limit );
		$rows = $db->loadObjectList();
		 
		//echo '<pre>';print_r($rows);die();
		
		$count = count( $rows );
		for ( $i = 0; $i < $count; $i++ )
		{
			$rows[$i]->href 	= JRoute::_(DJClassifiedsSEO::getItemRoute($rows[$i]->id.':'.$rows[$i]->alias,$rows[$i]->cat_id.':'.$rows[$i]->c_alias));
			$rows[$i]->section 	= JText::_('PLG_SEARCH_DJCLASSIFIEDS_DJCLASSIFIEDSITEMS').': '.$rows[$i]->category;
			$rows[$i]->browsernav = 2;
		}
		$return = array();
		
		foreach($rows as $key => $section) {
			if(searchHelper::checkNoHTML($section, $searchText, array('title', 'text', 'intro', 'metadesc', 'metakey'))) {
				$return[] = $section;
			}
		}
		//echo '<pre>';print_r($return);die();
		return $return;
	}
}

