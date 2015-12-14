<?php
/**
 * @version $Id: helper.php 42 2015-08-12 12:28:01Z szymon $
 * @package DJ-MegaMenu
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 * DJ-MegaMenu is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-MegaMenu is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-MegaMenu. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_ROOT . DS .'modules' . DS . 'mod_menu' . DS . 'helper.php');

class modDJMegaMenuHelper extends modMenuHelper {
	
	private static $subwidth = array();
	private static $subcols = array();
	private static $expand = array();
	private static $modules = null;
	private static $mobilemodules = null;
	
	public static function getActive(&$params) {
		
		$menu = JFactory::getApplication()->getMenu();

		// Get active menu item from parameters
		if ($params->get('active')) {
			$active = $menu->getItem($params->get('active'));
		} else {
			$active = false;
		}

		// If no active menu, use current or default
		if (!$active) {
			$active = ($menu->getActive()) ? $menu->getActive() : $menu->getDefault();
		}

		return $active;		
	}	
	
	public static function getList(&$params) {
		
		$list = parent::getList($params);
		
		// array with submenu wrapper widths
		if(!isset(self::$subwidth[$params->get('menu')])) {
			
			self::$subwidth[$params->get('menu')] = array();
			self::$subcols[$params->get('menu')] = array();
			self::$expand[$params->get('menu')] = array();
			
			$first = false;
			$parent = null;
			$hasSubtitles = false;
			$startLevel = $params->get('startLevel');
			
			foreach($list as $item) {
				
				if($parent || $item->params->get('djmegamenu-column_break',0)) {
					
					if($parent) {
						$parent->params->def('djmegamenu-first_column_width', $item->params->get('djmegamenu-column_width',$params->get('column_width')));
						$parent=null;
					}
					// calculate width of the sum
					if(!isset(self::$subwidth[$params->get('menu')][$item->parent_id])) self::$subwidth[$params->get('menu')][$item->parent_id] = 0;
					self::$subwidth[$params->get('menu')][$item->parent_id] += $item->params->get('djmegamenu-column_width',$params->get('column_width'));
					// count number of columns for this submenu
					if(!isset(self::$subcols[$params->get('menu')][$item->parent_id])) self::$subcols[$params->get('menu')][$item->parent_id] = 1;
					else self::$subcols[$params->get('menu')][$item->parent_id]++;
				}
				
				if($item->deeper) {
					$first = true;
					$parent = $item;
				}
				
				// load module if position set
				if($position = $item->params->get('djmegamenu-module_pos')) {
					$item->modules = self::loadModules($position,$item->params->get('djmegamenu-module_style','xhtml'));
				}
				// load module if position set
				if($position = $item->params->get('djmobilemenu-module_pos')) {
					$item->mobilemodules = self::loadModules($position,$item->params->get('djmobilemenu-module_style','xhtml'));
				}
				
				$subtitle = htmlspecialchars($item->params->get('djmegamenu-subtitle'));
				if(empty($subtitle) && $params->get('usenote')) $subtitle = htmlspecialchars($item->note);
				if($item->menu_image && !$item->params->get('menu_text', 1)) $subtitle = null;
				$item->params->set('djmegamenu-subtitle', $subtitle);
				
				if($item->level == $startLevel && !empty($subtitle)) $hasSubtitles = true;
				
				if($item->parent) self::$expand[$params->get('menu')][$item->id] = $item->params->get('djmegamenu-expand', 
						isset(self::$expand[$params->get('menu')][$item->parent_id]) ? self::$expand[$params->get('menu')][$item->parent_id] : $params->get('expand','dropdown'));
			}
			
			$params->def('hasSubtitles',$hasSubtitles);
		}
		
		return $list;
	}
	
	public static function getSubWidth(&$params) {
		
		if(!isset(self::$subwidth[$params->get('menu')])) self::getList($params);
		
		return self::$subwidth[$params->get('menu')];
	}
	
	public static function getSubCols(&$params) {
	
		if(!isset(self::$subcols[$params->get('menu')])) self::getList($params);
	
		return self::$subcols[$params->get('menu')];
	}
	
	public static function getExpand(&$params) {
		
		if(!isset(self::$expand[$params->get('menu')])) self::getList($params);
		
		return self::$expand[$params->get('menu')];
	}
	
	public static function loadModules($position, $style = 'xhtml')
	{
		if (!isset(self::$modules[$position])) {
			self::$modules[$position] = '';
			if($style == '0') $style = 'xhtml';
			$document	= JFactory::getDocument();
			$renderer	= $document->loadRenderer('module');
			$modules	= JModuleHelper::getModules($position);
			$params		= array('style' => preg_replace('/^[\w]+\-/i', '', $style));
			ob_start();
			
			foreach ($modules as $module) {
				echo $renderer->render($module, $params);
			}
	
			self::$modules[$position] = ob_get_clean();
		}
		return self::$modules[$position];
	}
}
?>