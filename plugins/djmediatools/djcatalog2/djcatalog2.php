<?php
/**
 * @version $Id: djcatalog2.php 42 2014-09-24 12:20:47Z szymon $
 * @package DJ-MediaTools
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 * DJ-MediaTools is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-MediaTools is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-MediaTools. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// no direct access
defined('_JEXEC') or die;

class plgDJMediatoolsDJCatalog2 extends JPlugin
{
	/**
	 * Plugin that returns the object list for DJ-Mediatools album
	 * 
	 * Each object must contain following properties (mandatory): title, description, image
	 * Optional properties: link, target (_blank or _self), alt (alt attribute for image)
	 * 
	 * @param	object	The album params
	 */
	public function onAlbumPrepare(&$source, &$params)
	{
		// Lets check the requirements
		$check = $this->onCheckRequirements($source);
		if (is_null($check) || is_string($check)) {
			return null;
		}
						
		$app = JFactory::getApplication();
		require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'route.php');
		require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'djcatalog2.php');
		
		JModelLegacy::addIncludePath(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'models');
		$model = JModelLegacy::getInstance('Items', 'Djcatalog2Model', array('ignore_request'=>true));
			
		$order		= $params->get('plg_catalog2_orderby','i.ordering');
		$order_Dir	= $params->get('plg_catalog2_orderdir','asc');
		$order_featured	= $params->get('plg_catalog2_featured_first', 0);
		$filter_catid		= $params->get('plg_catalog2_catid', array());
		$filter_itemids		= $params->get('plg_catalog2_item_ids', null);
		
		$filter_featured	= $params->get('plg_catalog2_featured_only', 0);
		$limit = $params->get('max_images');
		$default_image = $params->get('plg_catalog2_image');
		
		$cparams = $app->getParams('com_djcatalog2');
		$cparams->set('product_catalogue', 0);
		$model->setState('params', $cparams);
		
		$model->setState('list.start', 0);
		$model->setState('list.limit', $limit);
		
		$model->setState('filter.category',$filter_catid);
		$model->setState('filter.catalogue',false);
		$model->setState('filter.featured',$filter_featured);
		$model->setState('list.ordering_featured',$order_featured);
		$model->setState('list.ordering',$order);
		$model->setState('list.direction',$order_Dir);
		if($filter_itemids) {
			$filter_itemids = explode(',', $filter_itemids);
			JArrayHelper::toInteger($filter_itemids);
			$model->setState('filter.item_ids', $filter_itemids);
		}

		$items = $model->getItems();
		$slides = array();
		
		foreach($items as $item){
			
			$slide = (object) array();
			
			if(!empty($item->image_fullpath)) {
				$slide->image = 'media/djcatalog2/images/'.$item->image_fullpath;
			} else if(!empty($item->item_image)) {
				$slide->image = 'media/djcatalog2/images/'.$item->item_image;
			} else if(!empty($default_image)) {
				$slide->image = $default_image;
			} else {
				continue;
			}
			$slide->title = $item->name;
			$slide->description = $item->intro_desc;			
			$slide->canonical = $slide->link = JRoute::_(DJCatalogHelperRoute::getItemRoute($item->slug, $item->catslug));			
			$slide->alt = $item->image_caption;
			$slide->id = $item->slug;
			
			if($comments = $params->get('commnets',0)) {
				$host = str_replace(JURI::root(true), '', JURI::root());
				$host = preg_replace('/\/$/', '', $host);
				switch($comments) {
					case 1: // jcomments
						$slide->comments = array('id' => $item->id, 'group' => 'com_djcatalog2');
						break;
					case 2: // disqus
						$disqus_shortname = $params->get('disqus_shortname','');
						if(!empty($disqus_shortname)) {
							$slide->comments = array();
							$slide->comments['url'] =  $host . $slide->link;
							$slide->comments['identifier'] = $disqus_shortname.'-djc2-'.$item->id;
						}
						break;
					case 3: // facebook
						$slide->comments = $host . $slide->link;
						break;
					case 4: //komento
						// not implemented
						break;
				}
			}
			
			$slides[] = $slide;
		}
		
		return $slides;		
	}
	
	/*
	 * Define any requirements here (such as specific extensions installed etc.)
	 * 
	 * Returns true if requirements are met or text message about not met requirement
	 */
	public function onCheckRequirements(&$source) {
		
		// Don't run this plugin when the source is different
		if ($source != $this->_name) {
			return null;
		}
		
		if(!JFile::exists(JPATH_ROOT.'/components/com_djcatalog2/djcatalog2.php')) return JText::_('PLG_DJMEDIATOOLS_DJCATALOG2_COMPONENT_DISABLED');
		jimport('joomla.application.component.helper');
		$com = JComponentHelper::getComponent('com_djcatalog2', true);
		if(!$com->enabled) return JText::_('PLG_DJMEDIATOOLS_DJCATALOG2_COMPONENT_DISABLED');
		
		return true;		
	}

	function debug($data, $type = 'message') {
		
		$app = JFactory::getApplication();		
		$app->enqueueMessage("<pre>".print_r($data, true)."</pre>", $type);
		
	}
}
