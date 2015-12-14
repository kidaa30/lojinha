<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
* @copyright	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license		http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
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
class DJClassifiedsViewItems extends JViewLegacy
{
	function display($tpl = null)
	{
		$app	= JFactory::getApplication();
		$document = JFactory::getDocument();
		$Itemid = JRequest::getVar('Itemid', 0, 'int');
		//$document->link = JRoute::_(WeblinksHelperRoute::getCategoryRoute(JRequest::getVar('id', null, '', 'int')));

		JRequest::setVar('limit', $app->getCfg('feed_limit'));
		$siteEmail = $app->getCfg('mailfrom');
		$fromName = $app->getCfg('fromname');
		$document->editor = $fromName;
		$document->editorEmail = $siteEmail;
		
		// Get some data from the model
		$model = $this->getModel();		
		$cat_id	= JRequest::getVar('cid', 0, '', 'int');
		$catlist = '';
		$maincat = '';  
		if($cat_id>0){
			$main_cat= $model->getMainCat($cat_id);
			$document->title = $main_cat->name;		
			$cats= DJClassifiedsCategory::getSubCatIemsCount($cat_id,1);
			$catlist= $cat_id;			
			foreach($cats as $c){
				$catlist .= ','. $c->id;
			}				
		}
		
		$items= $model->getItems($catlist);

		foreach ($items as $item)
		{
			// strip html from feed item title
			$title = $this->escape($item->name);
			$title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');

			// url link to article
			//$link = JRoute::_('index.php?option=com_djclassifieds&view=item&cid='.$item->cat_id.'&id='.$item->id.'&Itemid='.$Itemid);
			if(!$item->alias){
				$item->alias = DJClassifiedsSEO::getAliasName($item->name);
			}
			if(!$item->c_alias){
				$item->c_alias = DJClassifiedsSEO::getAliasName($item->c_name);					
			}
			$link = JRoute::_(DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias));
			//echo $link;die();

			// strip html from feed item description text
			$description = $item->intro_desc;
			$date = ($item->date_start ? date('r', strtotime($item->date_start)) : '');

			if(count($item->images)){
				$description .= '<img align="right"  src="'.JURI::base().$item->images[0]->thumb_m.'" alt="'.str_ireplace('"', "'", $item->images[0]->caption).'"  />';
			}	
			// load individual item creator class
			$feeditem = new JFeedItem();
			$feeditem->title		= $title;
			$feeditem->link			= $link;
			$feeditem->description	= $description;
			$feeditem->date			= $date;
			$feeditem->category		= $item->c_name;

			// loads item info into rss array
			$document->addItem($feeditem);
		}
	}
}
?>
