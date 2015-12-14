<?php
/**
 * @version $Id: images.raw.php 65 2015-07-06 10:41:53Z szymon $
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

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controlleradmin');


class DJMediatoolsControllerImages extends JControllerLegacy
{
	public function purge() {
		
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		if (!$user->authorise('core.admin', 'com_djmediatools')){
			echo JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN');
			exit(0);
		}
		
		$files = JFolder::files(JPATH_ROOT.DS.'media'.DS.'djmediatools'.DS.'cache', '.', true, true, array('index.html', '.svn', 'CVS', '.DS_Store', '__MACOSX')); 
		$errors = array();
		if (count($files) > 0) {
			foreach ($files as $file) {
				if (!JFile::delete($file)){
					$errors[] = $db->quote(JPath::clean(str_replace(JPATH_ROOT, '', $file)));
				}
			}
		}
		$folders = JFolder::folders(JPATH_ROOT.DS.'media'.DS.'djmediatools'.DS.'cache', '.', true, true, array('.', '..'));
		if (count($folders) > 0) {
			$folders = array_reverse($folders);
			foreach ($folders as $key => $folder) {
				JFolder::delete($folder);
			}
		}
		
		
		
		if (count($errors) > 0) {
			$db->setQuery("DELETE FROM #__djmt_resmushit WHERE path NOT IN (".implode(',', $errors).")");
			$db->query();
			echo JText::sprintf('COM_DJMEDIATOOLS_N_IMAGES_HAVE_NOT_BEEN_DELETED', count($errors));
		} else {
			$db->setQuery("DELETE FROM #__djmt_resmushit");
			$db->query();
			echo JText::sprintf('COM_DJMEDIATOOLS_N_IMAGES_HAVE_BEEN_DELETED', count($files));
		}
	}
	
	public function purgeCSS() {
		$user = JFactory::getUser();
		if (!$user->authorise('core.admin', 'com_djmediatools')){
			echo JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN');
			exit(0);
		}
	
		$files = JFolder::files(JPATH_ROOT.DS.'media'.DS.'djmediatools'.DS.'css', '.', false, false, array('index.html', '.svn', 'CVS', '.DS_Store', '__MACOSX'));
		$errors = array();
		if (count($files) > 0) {
			foreach ($files as $file) {
				if (!JFile::delete(JPATH_ROOT.DS.'media'.DS.'djmediatools'.DS.'css'.DS.$file)){
					$errors[] = $file;
				}
			}
		}
		if (count($errors) > 0) {
			echo JText::sprintf('COM_DJMEDIATOOLS_N_STYLESHEETS_HAVE_NOT_BEEN_DELETED', count($errors));
		} else {
			echo JText::sprintf('COM_DJMEDIATOOLS_N_STYLESHEETS_HAVE_BEEN_DELETED', count($files));
		}
	}
	
	public function resmushit() {
		
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		if (!$user->authorise('core.admin', 'com_djmediatools')){
			echo JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN');
			exit(0);
		}
		
		$files = JFolder::files(JPATH_ROOT.DS.'media'.DS.'djmediatools'.DS.'cache', '.', true, true, array('index.html', '.svn', 'CVS', '.DS_Store', '__MACOSX'));
		
		$db = JFactory::getDbo();
		$path = null;
		
		foreach ($files as $key => $file) {
			$path = JPath::clean(str_replace(JPATH_ROOT, '', $file));
			$db->setQuery("SELECT * FROM #__djmt_resmushit WHERE path=".$db->quote($path));
			$obj = $db->loadObject();
			if(!$obj) break;
			if($key == count($files) - 1) {
				echo 'end';
				$app->close();
			}
		}
		
		// Losslessly compressing with resmush.it
		$url = 'http://www.resmush.it/ws.php';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('files' => '@' . $file));
		$data = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($data);
		
		if(isset($json->error)) {
			echo "reSmush.it Webservice Error ".$json->error." - ".$json->error_long;
			$app->close();
		} 
		
		// download and write file only if image size is smaller
		if($json->src_size > $json->dest_size) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $json->dest);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
			$image = curl_exec($ch);
			curl_close($ch);
			
			JFile::write($file, $image);
			
			$json->percent = 100 * ($json->src_size - $json->dest_size) / $json->src_size;
		}
		
		$db->setQuery("INSERT INTO #__djmt_resmushit (md5, path, original_size, size, percent) VALUES (".$db->quote(md5($path)).", ".$db->quote($path).", ".$json->src_size.", ".$json->dest_size.", ".$json->percent.")");
		$db->query();
		
		$db->setQuery("select count(*) from #__djmt_resmushit");
		$resmushed = $db->loadResult();
		
		$return = array();
		$return['path'] = $path;
		$return['percent'] = $json->percent;
		$return['total'] = count($files);
		$return['optimized'] = $resmushed;
		
		$document->setMimeEncoding('application/json');
		echo json_encode($return);
	}
}