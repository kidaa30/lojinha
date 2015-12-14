<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;

$themer = (int)$this->params->get('themermode', 0) == 1 ? true:false;
$devmode = (int)$this->params->get('devmode', 0) == 1 ? true:false;

// get direction
$direction = $this->params->get('direction', 'ltr');

// get information about responsive layout
$responsivelayout = $this->params->get('responsiveLayout', '1');

// get coming soon
$comingsoon = $this->params->get('comingSoon', '0');
$comingsoondate = $this->params->get('comingSoonDate');
$tz = new DateTimeZone(JFactory::getConfig()->get('offset', 'UTC'));
$server_date_cs = JFactory::getDate($comingsoondate, $tz);
$timestamp_cs = $server_date_cs->toUnix();
$server_date_now = JFactory::getDate(null, $tz);
$timestamp_now = $server_date_now->toUnix();
$futuredate = ($timestamp_now > $timestamp_cs) ? '0' : '1';

//get off-canvas 
$offcanvas = $this->params->get('offCanvas', '0');

$google_font_urls = array();
$generated_fonts = array();

// get google web font url for body font
$bodyfonttype = $this->params->get('bodyFontType', '1');
$bodygooglewebfonturl = htmlspecialchars($this->params->get('bodyGoogleWebFontUrl', $this->defaults->get('bodyGoogleWebFontUrl')));
$bodygeneratedwebfont = htmlspecialchars($this->params->get('bodyGeneratedWebFont'));

$google_font_urls[] = ($bodyfonttype == '1') ? $bodygooglewebfonturl : false;
$generated_fonts[] = ($bodyfonttype == '2' || $themer || $devmode) ? $bodygeneratedwebfont : false;

// get google web font url for module headings
$headingsfonttype = $this->params->get('headingsFontType', '1');
$headingsgooglewebfonturl = htmlspecialchars($this->params->get('headingsGoogleWebFontUrl', $this->defaults->get('headingsGoogleWebFontUrl')));
$headingsgeneratedwebfont = htmlspecialchars($this->params->get('headingsGeneratedWebFont'));

$google_font_urls[] = ($headingsfonttype == '1') ? $headingsgooglewebfonturl : false;
$generated_fonts[] = ($headingsfonttype == '2' || $themer || $devmode) ? $headingsgeneratedwebfont : false;

// get google web font url for article headings
$articlesfonttype = $this->params->get('articlesFontType', '1');
$articlesgooglewebfonturl = htmlspecialchars($this->params->get('articlesGoogleWebFontUrl', $this->defaults->get('articlesGoogleWebFontUrl')));
$articlesgeneratedwebfont = htmlspecialchars($this->params->get('articlesGeneratedWebFont'));

$google_font_urls[] = ($articlesfonttype == '1') ? $articlesgooglewebfonturl : false;
$generated_fonts[] = ($articlesfonttype == '2' || $themer || $devmode) ? $articlesgeneratedwebfont : false;

// get google web font url for dj-menu
$djmenufonttype = $this->params->get('djmenuFontType', '1');
$djmenugooglewebfonturl = htmlspecialchars($this->params->get('djmenuGoogleWebFontUrl', $this->defaults->get('djmenuGoogleWebFontUrl')));
$djmenugeneratedwebfont = htmlspecialchars($this->params->get('djmenuGeneratedWebFont'));

$google_font_urls[] = ($djmenufonttype == '1') ? $djmenugooglewebfonturl : false;
$generated_fonts[] = ($djmenufonttype == '2' || $themer || $devmode) ? $djmenugeneratedwebfont : false;

// get google web font url for advanced selectors
$advancedfonttype = $this->params->get('advancedFontType', '0');
$advancedgooglewebfonturl = htmlspecialchars($this->params->get('advancedGoogleWebFontUrl'));
$advancedgeneratedwebfont = htmlspecialchars($this->params->get('advancedGeneratedWebFont'));

$google_font_urls[] = ($advancedfonttype == '1') ? $advancedgooglewebfonturl : false;
$generated_fonts[] = ($advancedfonttype == '2' || $themer || $devmode) ? $advancedgeneratedwebfont : false;

// get favicon
$faviconimg = $this->params->get('favIconImg');

define('JMF_TH_TEMPLATE', $themer);
define('JMF_TH_BOOTSTRAP', $themer);

//page width
$templatefluidwidth = $this->params->get('JMfluidGridContainerLg', $this->defaults->get('JMfluidGridContainerLg'));
$gutterwidth = $this->params->get('JMbaseSpace', $this->defaults->get('JMbaseSpace'));
$widthtypepixels = (!strstr($templatefluidwidth, '%')) ? true : false;

// use latest ie engine
?>	
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($responsivelayout == "1") { 
// viewport fix for devices
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php } else { 
	if($widthtypepixels) { 
		$templatefluidwidth = str_replace('px', '', $templatefluidwidth);
		$gutterwidth = str_replace('px', '', $gutterwidth);
		$fullwidth = $templatefluidwidth + $gutterwidth;
	?>
	<meta name="viewport" content="width=<?php echo $fullwidth; ?>" />
	<?php } 
	} 
	
// load core head
?>
<jdoc:include type="head" />
<?php

// load bootstrap css
if ($direction == 'rtl') {
	$this->addCompiledStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/bootstrap_rtl.less'), true, JMF_TH_BOOTSTRAP);
	if ($responsivelayout == "1") {
		$this->addCompiledStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/bootstrap_responsive_rtl.less'), true, JMF_TH_BOOTSTRAP);
	}
} else {
	$this->addCompiledStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/bootstrap.less'), true, JMF_TH_BOOTSTRAP);
	if ($responsivelayout == "1") {
		$this->addCompiledStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/bootstrap_responsive.less'), true, JMF_TH_BOOTSTRAP);
	}
}

// load template css
$this->addCompiledStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/template.less'), true, JMF_TH_TEMPLATE);

// load offcanvas styles
if ($offcanvas == "1") {
	$this->addCompiledStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/offcanvas.less'), true, JMF_TH_TEMPLATE);
}
		
// load RTL styles
if ($direction == 'rtl') {
	$this->addCompiledStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/template_rtl.less'), true, JMF_TH_TEMPLATE);
}

//load coming-soon
if(($comingsoon!='0') && (!empty($comingsoondate)) && ($futuredate=='1') && JFactory::getApplication()->isSite() && JFactory::getUser()->guest) {
	$this->addCompiledStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/comingsoon.less'), true, JMF_TH_TEMPLATE);
}

// load responsive styles
if ($responsivelayout == "1") {
	$this->addCompiledStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/template_responsive.less'), true, JMF_TH_TEMPLATE);
}

if (!empty($google_font_urls)) {
	$google_font_urls = array_unique($google_font_urls);
	foreach($google_font_urls as $google_font) {
		if ($google_font) {
			$this->addStyleSheet($google_font);
		}
	}	
}

if (!empty($generated_fonts)) {
	$generated_fonts = array_unique($generated_fonts);
	foreach ($generated_fonts as $generated_font) {
		if ($generated_font) {
			$this->addGeneratedFont($generated_font);
		}
	}	
}

// load bootstrap scripts
JHtml::_('bootstrap.framework');

// load template scripts
$this->addScript(JMF_TPL_URL.'/'.'js'.'/'.'scripts.js');

// cache custom css
if ($url = $this->cacheStyleSheet('template_params.php')) {
	$this->document->addStyleSheet($url);
}

// load favicon
if ($faviconimg) { ?>
	<link href="<?php echo JURI::base().$faviconimg; ?>" rel="Shortcut Icon" />
<?php } else { ?>
	<link href="<?php echo JMF_TPL_URL ?>/images/favicon.ico" rel="Shortcut Icon" />
<?php }

// load injection code
echo $this->params->get('codeInjection');
 ?>