<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
?>
<?php // The menu class is deprecated. Use nav instead. ?>
<ul class="dj-mobile-nav <?php echo 'dj-mobile-'.$params->get('mobiletheme') . ' ' . $class_sfx;?>">
<?php
foreach ($list as $i => &$item)
{
	$class = 'dj-mobileitem itemid-' . $item->id;

	if (($item->id == $active_id) OR ($item->type == 'alias' AND $item->params->get('aliasoptions') == $active_id))
	{
		$class .= ' current';
	}
	
	if(!$params->get('accordion_collapsed')) {
		
		if (in_array($item->id, $path))
		{
			$class .= ' active';
		}
		elseif ($item->type == 'alias')
		{
			$aliasToId = $item->params->get('aliasoptions');
	
			if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
			{
				$class .= ' active';
			}
			elseif (in_array($aliasToId, $path))
			{
				$class .= ' alias-parent-active';
			}
		}
	}
	
	if ($item->type == 'separator')
	{
		$class .= ' divider';
	}

	if ($item->deeper)
	{
		$class .= ' deeper';
	}

	if ($item->parent)
	{
		$class .= ' parent';
	}
	
	if($item->params->get('djmegamenu-show') == 'mega') {
		$class .= ' dj-hideitem';
	}
		
	if (!empty($class))
	{
		$class = ' class="' . trim($class) . '"';
	}

	echo '<li' . $class . '>';

	if($item->params->get('djmobilemenu-module_show_link',0) || (!isset($item->mobilemodules) && !isset($item->modules))) {
		// Render the menu item.
		require JModuleHelper::getLayoutPath('mod_djmegamenu', 'default_mobile_url');
	}
	if(isset($item->mobilemodules)) {
		echo '<div class="modules-wrap">'.$item->mobilemodules.'</div>';
	}
	
	// The next item is deeper.
	if ($item->deeper)
	{
		echo '<ul class="dj-mobile-nav-child">';
	}
	elseif ($item->shallower)
	{
		// The next item is shallower.
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	else
	{
		// The next item is on the same level.
		echo '</li>';
	}
}
?></ul>
