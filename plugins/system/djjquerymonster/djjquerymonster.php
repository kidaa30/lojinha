<?php
/**
 * @version $Id: djjquerymonster.php 5 2015-05-18 23:17:56Z szymon $
 * @package DJ-jQueryMonster
 * @copyright Copyright (C) 2012-2015 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 * DJ-jQueryMonster is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-jQueryMonster is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-jQueryMonster. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// no direct access
defined('_JEXEC') or die ;

// import library dependencies
jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');

class plgSystemDJjQueryMonster extends JPlugin {

	protected $jquery = '';
	protected $jqueryui = '';
	protected $jquerycss = '';
	protected $jquerynoconflict = '';
	protected $check = true;

	public function __construct(&$subject, $config) {

		parent::__construct($subject, $config);

		$this->loadLanguage();

		$this->_enabled = true;
	}
	
	function onAfterRoute() {

		$app = JFactory::getApplication();

		if (JFactory::getDocument()->getType() !== 'html' || $app->isAdmin()) {
			$this->check = false;
			return;
		}

		if (!$this->params->get('jquery', 0)) {
			return;
		}
		$document = JFactory::getDocument();

		$version = $this->params->get('version', '1.8');
		if($version == 'customver') {
			$version = trim($this->params->get('customver', '1.8'));
		} else {
			// backward compatibility
			$subversion = $this->params->get('subversion', '');
			if ($subversion != '') {
				$version = '.' . $subversion;
			}
		}

		$protocol = $this->params->get('protocol', 'none');
		$protocol = ($protocol == 'none') ? '' : $protocol . ':';

		$compressed = '';
		if ($this->params->get('compression', 1)) {
			$compressed = '.min';
		}

		// jQuery
		if ($version == 'joomla') {
			$this->jquery = JURI::root(true) . '/media/jui/js/jquery'.$compressed.'.js';
		} else if ($version == 'custom') {
			$custompath = trim($this->params->get('custompath', ''));
			if ($custompath)
				$this->jquery = $custompath;
		} else {
			$this->jquery = $protocol . "//ajax.googleapis.com/ajax/libs/jquery/" . $version . "/jquery" . $compressed . ".js";
		}

		if (!empty($this->jquery)) {
			$document->addScript("DJHOLDER_JQUERY");
		}

		// no conflict
		$document->addScript("DJHOLDER_NOCONFLICT");
		if ($version == 'joomla') {
			$this->noconflict = JURI::root(true) . '/media/jui/js/jquery-noconflict.js';
		} else {
			$this->noconflict = JURI::root(true) . "/plugins/system/djjquerymonster/assets/jquery.noconflict.js";
		}
		
		$app->set('jQuery', true);

		if (!$this->params->get('jqueryui', 0)) {
			return;
		}

		$uiversion = $this->params->get('uiversion', '1.9.2');
		if($uiversion == 'customver') {
			$uiversion = trim($this->params->get('customuiver', '1.9.2'));
		} else {
			// backward compatibility
			$uisubversion = $this->params->get('uisubversion', '');
			if ($uisubversion != '') {
				$uiversion = '.' . $uisubversion;
			}
		}
		
		// jQuery UI
		if ($uiversion == 'joomla') {
			$this->jqueryui = JURI::root(true) . '/media/jui/js/jquery.ui.core'.$compressed.'.js';
		} else if ($uiversion == 'custom') {
			$custompath = trim($this->params->get('customuipath', ''));
			if ($custompath)
				$this->jqueryui = $custompath;
		} else {
			$this->jqueryui = $protocol . "//ajax.googleapis.com/ajax/libs/jqueryui/" . $uiversion . "/jquery-ui" . $compressed . ".js";
		}

		if (!empty($this->jqueryui)) {
			$document->addScript("DJHOLDER_JQUERYUI");
		}
		
		$uitheme = $this->params->get('uitheme', 'base');
		
		// jQuery UI theme
		if ($uitheme != 'none') {
			if ($uitheme == 'custom') {
				$custompath = trim($this->params->get('uithemecustom', ''));
				if ($custompath)
					$this->jquerycss = $custompath;
			} else {
				$this->jquerycss = $protocol . "//ajax.googleapis.com/ajax/libs/jqueryui/" . $uiversion . "/themes/" . $uitheme . "/jquery-ui.css";
			}

			if (!empty($this->jquerycss)) {
				$document->addStyleSheet("DJHOLDER_CSS");
			}
		}

	}

	function onBeforeCompileHead() {

		if (!$this->check) {
			return;
		}

		$app = JFactory::getApplication();
		$document = JFactory::getDocument();

		if ($app->isAdmin())
			return;
		
		$tplparams = $app->getTemplate(true)->params;
		$ef4compress = (defined('JMF_JQUERYMONSTER') && $tplparams->get('jsCompress','0')=='1' && !($tplparams->get('devmode',0) || JDEBUG || $app->input->get('option')=='com_config')) ? true : false;
		
		$headerdata = $document->getHeadData();
		$scripts = $headerdata['scripts'];
		$headerdata['scripts'] = array();

		if($ef4compress) { // if ef4 js compression is active we need to clean up scripts here
			
			// placeholder for ef4 compressed javascript
			$scripts["DJHOLDER_EF4COMPRESS"] = array('mime' => 'text/javascript', 'defer' => false, 'async' => false);
			
			$protects = trim((string)$this->params->get('protectscripts', ''));
			if ($protects) {
				$protects = array_map('trim', (array) explode("\n", $protects));
			}
				
			foreach ($scripts as $url => $type) {
				if(!empty($this->jquery) && preg_match('#([~\\\/a-zA-Z0-9_:\.-]*)jquery[.-]no[.-]*[cC]onflict\.js#', $url)) {
					unset($scripts[$url]);
				} else if(!empty($this->jquery) && preg_match('#([~\\\/a-zA-Z0-9_:\.-]*)\/jquery([0-9\.-]|core|min|pack|latest)*?.js#', $url, $match)) {
					$protect = false;
					if ($protects) {
						foreach ($protects as $script) {
							if (stripos($match[0], $script) !== false) {
								$protect = true;
							}
						}
					}
					if (!$protect) {
						unset($scripts[$url]);
					}
				} else if (!empty($this->jqueryui) && preg_match('#([~\\\/a-zA-Z0-9_:\.-]*)jquery[\.-]ui([0-9\.-]|core|custom|min|pack)*?.js#', $url)) {
					unset($scripts[$url]);
				}
			}
		}
		
		// first jquery, jquery-noconflict and jquery ui
		foreach ($scripts as $url => $type) {
			if (preg_match('#DJHOLDER_#s', $url)) {
				$headerdata['scripts'][$url] = $type;
				unset($scripts[$url]);
			}
		}
		
		// then mootools and all system scripts
		$qpath = preg_quote('media/system/js/', '/');
		foreach ($scripts as $url => $type) {
			if (preg_match('#' . $qpath . '#s', $url)) {
				$headerdata['scripts'][$url] = $type;
				unset($scripts[$url]);
			}
		}
		
		// then all joomla UI scripts
		$qpath = preg_quote('media/jui/js/', '/');
		foreach ($scripts as $url => $type) {
			if (preg_match('#' . $qpath . '#s', $url)) {
				$headerdata['scripts'][$url] = $type;
				unset($scripts[$url]);
			}
		}

		// and all other scripts
		foreach ($scripts as $url => $type) {
			$headerdata['scripts'][$url] = $type;
		}
		
		$document->setHeadData($headerdata);
	}

	function onAfterRender() {

		if (!$this->check) {
			return;
		}

		$app = JFactory::getApplication();

		$body = JResponse::getBody();

		if ($this->params->get('jquery', 0)) {

			$matches = array();
			if (preg_match_all('#[^}^;^\n^>]*(jQuery|\$)\.noConflict\((true|false|)\);#', $body, $matches, PREG_SET_ORDER) > 0) {
				
				$qjavascript = preg_quote('<script type="text/javascript">', '/');

				foreach ($matches as $match) {
					$qmatch = preg_quote($match[0], '#');

					if (preg_match('#(' . $qjavascript . '[\S\s]*?' . $qmatch . ')#', $body)) {
						$body = preg_replace('#' . $qmatch . '#', '', $body, 1);
					}
				}

				$body = preg_replace('#<script type="text/javascript">\s*</script>#', '', $body, -1);
			}

			$body = preg_replace('#src="([~\\\/a-zA-Z0-9_:\.-]*)jquery[.-]no[.-]*[cC]onflict\.js"#', '_REMOVE_', $body);

			$protects = trim((string)$this->params->get('protectscripts', ''));
			if ($protects) {
				$protects = array_map('trim', (array) explode("\n", $protects));
			}

			$matches = array();
			if (preg_match_all('#src="([~\\\/a-zA-Z0-9_:\.-]*)\/jquery([0-9\.-]|core|min|pack|latest)*?.js"#', $body, $matches, PREG_SET_ORDER) > 0) {
				foreach ($matches as $match) {
					$qmatch = preg_quote($match[0], '/');
					$protect = false;
					if ($protects) {
						foreach ($protects as $script) {
							if (stripos($match[0], $script) !== false) {
								$protect = true;
							}
						}
					}
					if (!$protect) {
						$body = preg_replace('#' . $qmatch . '#', '_REMOVE_', $body, 1);
					}
				}
			}
			//print_r($matches);
			if (!empty($this->jquery)) {
				$body = preg_replace('#([\\\/a-zA-Z0-9_:\.-]*)DJHOLDER_JQUERY#', $this->jquery, $body, 1);
			}
			$body = preg_replace('#([\\\/a-zA-Z0-9_:\.-]*)DJHOLDER_NOCONFLICT#', $this->noconflict, $body, 1);
			if ($this->params->get('jqueryui', 0)) {
				$body = preg_replace('#src="([~\\\/a-zA-Z0-9_:\.-]*)jquery[\.-]ui([0-9\.-]|core|custom|min|pack)*?.js"#', '_REMOVE_', $body);
				if (!empty($this->jqueryui)) {
					$body = preg_replace('#([\\\/a-zA-Z0-9_:\.-]*)DJHOLDER_JQUERYUI#', $this->jqueryui, $body, 1);
				}
				$body = preg_replace('#href="([\\\/a-zA-Z0-9_:\.-]*)jquery[\.-]ui([0-9\.-]|core|custom|min|pack)*?.css"#', '_REMOVE_', $body);
				$body = preg_replace('#[\s]*<link[^>]*_REMOVE_[^>]*/>[\s]*\n#', "\n", $body);

				if (!empty($this->jquerycss)) {
					$body = preg_replace('#([\\\/a-zA-Z0-9_:\.-]*)DJHOLDER_CSS#', $this->jquerycss, $body, 1);
				}
			}
			$body = preg_replace('#[\s]*<script[^>]*_REMOVE_[^>]*></script>[\s]*\n#', "\n", $body);
		}

		JResponse::setBody($body);

		return true;
	}

	private function dd($data, $type = 'message') {
		if($type == 'inline') {
			echo "<pre>".print_r($data, true)."</pre>";
		} else {
			JFactory::getApplication()->enqueueMessage("<pre>".print_r($data, true)."</pre>", $type);
		}
	}
	
}
