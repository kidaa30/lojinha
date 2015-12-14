<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
class Com_DJClassifiedsInstallerScript
{
	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * preflight runs before anything else and while the extracted files are in the uploaded temp folder.
	 * If preflight returns false, Joomla will abort the update and undo everything already done.
	 */
	function preflight( $type, $parent ) {
		$jversion = new JVersion();
		
		if(version_compare($this->getParam('version'), '2.0', 'lt')) {
			
			$db = JFactory::getDBO();
			$db->setQuery('SELECT extension_id FROM #__extensions WHERE name = "com_djclassifieds"');
			$ext_id = $db->loadResult();
			// adding the schema version before update to 2.0+
			if($ext_id) {
				$db->setQuery("INSERT INTO #__schemas (extension_id, version_id) VALUES (".$ext_id.", '1.1')");
				$db->query();
			}
		}
		
		$spacer = JPATH_ROOT.'/administrator/components/com_djclassifieds/models/fields/djspacer.php';		
		if (JFile::exists($spacer)){
			JFile::delete($spacer);				
		}

	}
	
	function postflight( $type, $parent ) {			
		$db = JFactory::getDBO();
		$db->setQuery("UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND element='djcfquickicon'");
		$db->query();
	}
	
	function getParam( $name ) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_djclassifieds"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}
}
