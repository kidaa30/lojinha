<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;

// get direction
$direction = $this->params->get('direction', 'ltr');

//check width type
$templatefluidwidth = $this->params->get('JMfluidGridContainerLg');
$templatewidthtype = $this->params->get('JMtemplateWidthType', 'fixed');

// responsive
$responsivelayout = $this->params->get('responsiveLayout', '1');
$responsivedisabled = ($responsivelayout != '1') ? 'responsive-disabled' : '';

// custom classes
$stickybar = ($this->params->get('stickyBar', '0')) ? 'sticky-bar' : '';
$topbar = ($this->checkModules('top-bar')) ? 'top-bar' : '';
$topmod = ($this->checkModules('top-mod1') OR $this->checkModules('top-mod2') OR $this->checkModules('top-menu-nav')) ? 'top-mod' : '';

//coming soon
$comingsoon = $this->params->get('comingSoon', '0');
$comingsoondate = $this->params->get('comingSoonDate');

$tz = new DateTimeZone(JFactory::getConfig()->get('offset', 'UTC'));
$server_date_cs = JFactory::getDate($comingsoondate, $tz);
$timestamp_cs = $server_date_cs->toUnix();
$server_date_now = JFactory::getDate(null, $tz);
$timestamp_now = $server_date_now->toUnix();
$futuredate = ($timestamp_now > $timestamp_cs) ? '0' : '1';

//offcanvas
// get offcanvas
$offcanvas = $this->params->get('offCanvas', '0');

// get off-canvas position
$offcanvasside = ($offcanvas == '1') ? $this->params->get('offCanvasPosition', $this->defaults->get('offCanvasPosition')) : '';
if ($offcanvasside == 'right') {
	$offcanvasposition = 'off-canvas-right';
} else if ($offcanvasside == 'left') {
	$offcanvasposition = 'off-canvas-left';
} else {
	$offcanvasposition = '';
}

// define default blocks and their default order (can be changed in layout builder)
$blocks = $this->getBlocks('topbar,header,system-message,top-mod,top,main,bottom1,bottom2,bottom3,footer-mod,footer', 'comingsoon');

//chosen for DJ-Classifieds Search module (front)
if($this->countModules('header')) {
  JHtml::_('formbehavior.chosen', '.category-ms .dj_cf_search .search_cats > select');
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $direction; ?>">
<head>
	<?php $this->renderBlock('head'); ?>
</head>
<body class="<?php echo $responsivedisabled.' '.$templatewidthtype.' '.$stickybar.' '.$topbar.' '.$topmod.' '.$offcanvasposition; ?>">
	<div id="jm-allpage">
		<div id="jm-page">
		<?php if(($comingsoon!='0') && (!empty($comingsoondate)) && ($futuredate=='1') && JFactory::getApplication()->isSite() && JFactory::getUser()->guest) {
  			$this->renderBlock('comingsoon'); 
		} else { ?>
			<?php if($offcanvas == '1') : ?>
				<?php $this->renderBlock('offcanvas'); ?>
			<?php endif; ?>
			<?php foreach($blocks as $block) { ?>
				<?php $this->renderBlock($block); ?>
			<?php } ?>
		<?php } ?>
		</div>
	</div>
</body>
</html>