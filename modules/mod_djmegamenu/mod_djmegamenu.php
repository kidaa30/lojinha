<?php 
/**
 * @version $Id: mod_djmegamenu.php 40 2015-07-29 23:45:14Z szymon $
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
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// Include the syndicate functions only once
require_once (dirname(__FILE__) . DS . 'helper.php');

$params->def('menutype', $params->get('menu','mainmenu'));
$params->def('startLevel', 1);
$params->def('endLevel', 0);
$params->def('showAllChildren', 1);
$params->def('mobiletheme', 'dark');
$params->set('column_width', (int)$params->get('column_width',200));
$params->def('fixed_logo', 0);
$params->def('fixed_logo_align', 'right');
$params->def('width', 979);
$params->def('select_type', 'button');
$params->def('accordion_pos', 'static');
$params->def('accordion_align', 'center');
$params->def('accordion_collapsed', 0);
$params->def('icons', '2');
$params->def('subtitles', '2');
$startLevel = $params->get('startLevel');
$endLevel = $params->get('endLevel');

$list		= modDJMegaMenuHelper::getList($params);
$subwidth	= modDJMegaMenuHelper::getSubWidth($params);
$subcols	= modDJMegaMenuHelper::getSubCols($params);
$expand		= modDJMegaMenuHelper::getExpand($params);
$active		= modDJMegaMenuHelper::getActive($params);
$active_id 	= $active->id;
$path		= $active->tree;

$showAll	= $params->get('showAllChildren');
$class_sfx	= ($params->get('hasSubtitles') ? 'hasSubtitles ':'') . htmlspecialchars($params->get('moduleclass_sfx'));

if(!count($list)) return;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$direction = $doc->direction;
//$app->enqueueMessage("<pre>".print_r($parents, true)."</pre>");
$version = new JVersion;
$jquery = version_compare($version->getShortVersion(), '3.0.0', '>=');
$canDefer = preg_match('/(?i)msie [6-9]/',$_SERVER['HTTP_USER_AGENT']) ? false : true;
$defercss = array();

if ($jquery) {
	JHTML::_('jquery.framework');
} else {
	JHTML::_('behavior.framework', true);
}

// direction integration with joomla monster templates
if ($app->input->get('direction') == 'rtl'){
	$direction = 'rtl';
} else if ($app->input->get('direction') == 'ltr') {
	$direction = 'ltr';
} else {
	if (isset($_COOKIE['jmfdirection'])) {
		$direction = $_COOKIE['jmfdirection'];
	} else {
		$direction = $app->input->get('jmfdirection', $direction);
	}
}

if($params->get('theme')!='_override') {
	$css = 'modules/mod_djmegamenu/themes/'.$params->get('theme','default').'/css/djmegamenu.css';
} else {
	$params->set('theme', 'override');
	$css = 'templates/'.$app->getTemplate().'/css/djmegamenu.css';
}

// load theme only if file exists or ef4 template in use
if(JFile::exists(JPATH_ROOT . DS . $css) || defined('JMF_EXEC')) {
	$doc->addStyleSheet(JURI::root(true).'/'.$css);
}
if($direction == 'rtl') { // load rtl theme css if file exists or ef4 template in use
	$css_rtl = JFile::stripExt($css).'_rtl.css';
	if(JFile::exists(JPATH_ROOT . DS . $css_rtl) || defined('JMF_EXEC')) {
		$doc->addStyleSheet(JURI::root(true).'/'.$css_rtl);
	}
}

$doc->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
//$defercss[] = '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css';

if($params->get('moo',1)) {
	
	$doc->addStyleSheet(JURI::root(true).'/modules/mod_djmegamenu/assets/css/animations.css');
	//$doc->addStyleSheet(JURI::root(true).'/media/djextensions/css/animate.min.css');
	//$doc->addStyleSheet(JURI::root(true).'/media/djextensions/css/animate.ext.css');
	$defercss[] = JURI::root(true).'/media/djextensions/css/animate.min.css';
	$defercss[] = JURI::root(true).'/media/djextensions/css/animate.ext.css';
	$doc->addScript(JURI::root(true).'/modules/mod_djmegamenu/assets/js/'.($jquery ? 'jquery.':'').'djmegamenu.js', 'text/javascript', $canDefer);
	
	if(!is_numeric($delay = $params->get('delay'))) $delay = 500;
	$wrapper_id = $params->get('wrapper');
	$animIn = $params->get('animation_in');
	$animOut = $params->get('animation_out');
	$animSpeed = $params->get('animation_speed');
	$open_event = $params->get('event','mouseenter');
	$fixed = $params->get('fixed',0);
	$fixed_offset = $params->get('fixed_offset',0);
	$theme = $params->get('theme');
	
	$options = json_encode(array('wrap' => $wrapper_id, 'animIn' => $animIn, 'animOut' => $animOut, 'animSpeed' => $animSpeed, 'delay' => $delay, 
		'event' => $open_event, 'fixed' => $fixed, 'offset' => $fixed_offset, 'theme' => $theme, 'direction' => $direction ));
}

$mobilemenu = (int) $params->get('select',0);
if($mobilemenu) {

	$doc->addScript(JURI::root(true).'/modules/mod_djmegamenu/assets/js/'.($jquery ? 'jquery.':'').'djmobilemenu.js', 'text/javascript', $canDefer);
	$doc->addStyleDeclaration("
		#dj-megamenu$module->id"."mobile { display: none; }
		@media (max-width: ".(int)$params->get('width')."px) {
			#dj-megamenu$module->id, #dj-megamenu$module->id"."sticky, #dj-megamenu$module->id"."placeholder { display: none; }
			#dj-megamenu$module->id"."mobile { display: block; }
		}
	");

	if($mobilemenu=='2') {
		$doc->addStyleSheet(JURI::root(true).'/modules/mod_djmegamenu/assets/css/offcanvas.css');
		$offmodules = array();
		$offmodules['top'] = modDJMegaMenuHelper::loadModules('dj-offcanvas-top', $params->get('offcanvas_topmod_style','xhtml'));
		$offmodules['bottom'] = modDJMegaMenuHelper::loadModules('dj-offcanvas-bottom', $params->get('offcanvas_botmod_style','xhtml'));
	}

	if($params->get('mobiletheme')!='_override') {
		$css = 'modules/mod_djmegamenu/mobilethemes/'.$params->get('mobiletheme','dark').'/djmobilemenu.css';
	} else {
		$params->set('mobiletheme', 'override');
		$css = 'templates/'.$app->getTemplate().'/css/djmobilemenu.css';
	}
	
	// add only if theme file exists
	if(JFile::exists(JPATH_ROOT . DS . $css)) {
		$doc->addStyleSheet(JURI::root(true).'/'.$css);
	}
	if($direction == 'rtl') { // load rtl css if exists in theme or joomla template
		$css_rtl = JFile::stripExt($css).'_rtl.css';
		if(JFile::exists(JPATH_ROOT . DS . $css_rtl)) {
			$doc->addStyleSheet(JURI::root(true).'/'.$css_rtl);
		}
	}
}

if(count($defercss)) {
	$js = "
	(function(){
		var cb = function() {
			var add = function(css) {
				var l = document.createElement('link'); l.rel = 'stylesheet';
				l.href = css;
				var h = document.getElementsByTagName('head')[0]; h.appendChild(l);
			}";
		foreach($defercss as $css) {
			$js .= "
			add('$css');";
		}
	$js .= "
		};
		var raf = requestAnimationFrame || mozRequestAnimationFrame || webkitRequestAnimationFrame || msRequestAnimationFrame;
		if (raf) raf(cb);
		else window.addEventListener('load', cb);
	})();";
	$doc->addScriptDeclaration($js);
}

require(JModuleHelper::getLayoutPath('mod_djmegamenu', $params->get('layout', 'default')));

?>