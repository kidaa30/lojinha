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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

class plgDJClassifiedsRegistrationInstallerScript
{
    protected $packages = array();
    protected $sourcedir;
    protected $installerdir;
    protected $manifest;
    protected function setup($parent){
        $this->sourcedir = $parent->getParent()->getPath('source');
        $this->manifest = $parent->getParent()->getManifest();
        $this->installerdir = $this->sourcedir . DS . 'installer';
    }
    public function install($parent){
    	$view_xml = JPATH_ROOT.'/components/com_djclassifieds/views/registration/tmpl/';
    	if(JFile::exists($view_xml.'_default.xml')){
    		rename($view_xml.'_default.xml', $view_xml.'default.xml');
    	}
    }
        
    public function update($parent){
    	$view_xml = JPATH_ROOT.'/components/com_djclassifieds/views/registration/tmpl/';
    	if(JFile::exists($view_xml.'_default.xml')){
    		rename($view_xml.'_default.xml', $view_xml.'default.xml');
    	}
    }

    public function install($parent){
    	$view_xml = JPATH_ROOT.'/components/com_djclassifieds/views/registration/tmpl/';
    	if(JFile::exists($view_xml.'default.xml')){
    		rename($view_xml.'default.xml', $view_xml.'_default.xml');
    	}
    }
    
}