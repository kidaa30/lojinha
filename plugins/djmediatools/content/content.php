<?php
/**
 * @version $Id: content.php 42 2014-09-24 12:20:47Z szymon $
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

class plgDJMediatoolsContent extends JPlugin
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
		
		$max = $params->get('max_images');
        $catid = (int) $params->get('plg_content_id',0);
		$default_image = $params->get('plg_content_image');
		
		require_once(JPATH_BASE.'/components/com_content/helpers/route.php');
		JModelLegacy::addIncludePath(JPATH_BASE.'/components/com_content/models', 'ContentModel');
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request'=>true));
		$model->setState('params', $app->getParams('com_content'));
		$model->setState('list.select',
		'a.id, a.title, a.alias, a.introtext, a.fulltext, ' .
		'a.checked_out, a.checked_out_time, ' .
		'a.catid, a.created, a.created_by, a.created_by_alias, ' .
		// use created if modified is 0
		'CASE WHEN a.modified = 0 THEN a.created ELSE a.modified END as modified, ' .
		'a.modified_by, uam.name as modified_by_name,' .
		// use created if publish_up is 0
		'CASE WHEN a.publish_up = 0 THEN a.created ELSE a.publish_up END as publish_up,' .
		'a.publish_down, a.images, a.urls, a.attribs, a.metadata, a.metakey, a.metadesc, a.access, ' .
		'a.hits, a.xreference, a.featured');
				
		$model->setState('list.start', 0);
		$model->setState('list.limit', $max);
		if($params->get('sort_by')) {
			$model->setState('list.ordering', $params->get('plg_content_order','a.ordering'));
			$model->setState('list.direction', $params->get('plg_content_order_dir','ASC'));
		} else {
			$model->setState('list.ordering', 'RAND()');
		}
		$user = JFactory::getUser();
		if ((!$user->authorise('core.edit.state', 'com_content')) &&  (!$user->authorise('core.edit', 'com_content'))){
			// filter on published for those who do not have edit or edit.state rights.
			$model->setState('filter.published', 1);
		}
		$model->setState('filter.language', $app->getLanguageFilter());
		// check for category selection
		if ($catid) {
			$model->setState('filter.category_id', $catid);
		}
		if($params->get('plg_content_type')=='featured') $model->setState('filter.featured', 'only');
		
		$showSubcategories = $params->get('plg_content_maxlevel', '0');
		if ($showSubcategories) {
			$model->setState('filter.subcategories', true);
			$model->setState('filter.max_category_levels', $params->get('plg_content_maxlevel', '1'));
		}
		
		$items = $model->getItems();
		$slides = array();
		
		foreach($items as $item){
			$slide = (object) array();
			
			$images = new JRegistry($item->images); 
			if($images->get('image_intro')) $slide->image = $images->get('image_intro');
			else if($images->get('image_fulltext')) $slide->image = $images->get('image_fulltext');
			else $slide->image = DJMediatoolsLayoutHelper::getImageFromText($item->introtext);
			//djdebug($item->fulltext);			
			// if no image found in article images and introtext then try fulltext
			if(!$slide->image) $slide->image = DJMediatoolsLayoutHelper::getImageFromText($item->fulltext);
			// if no image found in fulltext then take default image
			if(!$slide->image) $slide->image = $default_image;
			// if no default image set then don't display this article
			if(!$slide->image) continue;

			$slide->title = $item->title;
			$slide->description = $item->introtext;
			if(empty($slide->description)) $slide->description = $item->fulltext;
			
			$slide->id = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
			$slide->canonical = $slide->link = JRoute::_(ContentHelperRoute::getArticleRoute($slide->id, $item->catid));
			
			if($comments = $params->get('commnets',0)) {
				$host = str_replace(JURI::root(true), '', JURI::root());
				$host = preg_replace('/\/$/', '', $host);
				switch($comments) {
					case 1: // jcomments
						$slide->comments = array('id' => $item->id, 'group' => 'com_content');
						break;
					case 2: // disqus
						$disqus_shortname = $params->get('disqus_shortname','');
						if(!empty($disqus_shortname)) {
							$slide->comments = array();
							$slide->comments['url'] =  $host . $slide->link;
							$slide->comments['identifier'] = substr(md5($disqus_shortname), 0, 10)."_id".$item->id;
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
		
		return true;		
	}
	
}
