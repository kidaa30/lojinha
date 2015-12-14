<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;

class JMTemplate extends JMFTemplate {
	public function postSetUp() {

		// ---------------------------------------------------------
		// LESS MAP
		// ---------------------------------------------------------
		
		// --------------------------------------
		// BOOTSTRAP
		// --------------------------------------
		
		$this->lessMap['bootstrap.less'] = array(
			'bootstrap_variables.less', 
            'template_variables.less',  
			'override/ltr/accordion.less',
			'override/ltr/breadcrumbs.less',
			'override/ltr/button-groups.less',
			'override/ltr/buttons.less',
			'override/ltr/dropdowns.less',
			'override/ltr/forms.less',
            'override/ltr/navbar.less',
			'override/ltr/navs.less',
			'override/ltr/pager.less',
			'override/ltr/pagination.less',
            'override/ltr/scaffolding.less',
			'override/ltr/tables.less',
            'override/ltr/type.less',
			'override/ltr/utilities.less',
        );
        
        $this->lessMap['bootstrap_rtl.less'] = array(
            'bootstrap_variables.less', 
            'template_variables.less',  
			'override/rtl/accordion.less',
			'override/rtl/breadcrumbs.less',
			'override/rtl/button-groups.less',
			'override/rtl/buttons.less',
			'override/rtl/dropdowns.less',
			'override/rtl/forms.less',
            'override/rtl/navbar.less',
			'override/rtl/navs.less',
			'override/rtl/pager.less',
			'override/rtl/pagination.less',
            'override/rtl/scaffolding.less',
			'override/rtl/tables.less',
            'override/rtl/type.less',
			'override/rtl/utilities.less',
        );
        
        $this->lessMap['bootstrap_responsive.less'] = array(
            'bootstrap_variables.less', 
            'override/ltr/responsive-767px-max.less'
        );
        
        $this->lessMap['bootstrap_responsive_rtl.less'] = array(
            'bootstrap_variables.less', 
            'override/rtl/responsive-767px-max.less'
        );
        
		// --------------------------------------
		// TEMPLATE
		// --------------------------------------
		
        $this->lessMap['template.less'] = array(
            'bootstrap_variables.less', 
            'template_variables.less',
			'override/ltr/buttons.less', 
            'override/ltr/mixins.less', 
            'template_mixins.less',
			//template
			'animated_buttons.less',
			'editor.less',
			'joomla.less',
            'layout.less',
			'menus.less',
            'modules.less',
			//extensions
			'djmediatools.less'
        );
		
		$this->lessMap['template_rtl.less'] = array(
            'bootstrap_variables.less',
            'template_variables.less',
			'override/rtl/buttons.less',
            'override/rtl/mixins.less', 
            'template_mixins.less',
			//extensions
			'djmediatools_rtl.less'
        );

		$this->lessMap['template_responsive.less'] = array(
            'bootstrap_variables.less', 
            'template_variables.less', 
			'override/ltr/buttons.less',
            'override/ltr/mixins.less', 
            'template_mixins.less',
			//extensions
			'djmediatools_responsive.less'
        );
		
		// other files
		// ---------------------------
		
		$common_ltr = array(
			'bootstrap_variables.less',
			'template_variables.less',
			'override/ltr/buttons.less',
			'override/ltr/mixins.less',
			'template_mixins.less'
		);
		
		$common_rtl = array(
			'bootstrap_variables.less',
			'template_variables.less',
			'override/rtl/buttons.less',
			'override/rtl/mixins.less',
			'template_mixins.less'
		);
		
		$this->lessMap['comingsoon.less'] = $common_ltr;
		$this->lessMap['offcanvas.less'] = $common_ltr;
		$this->lessMap['offline.less'] = $common_ltr;
		$this->lessMap['custom.less'] = $common_ltr;
		
		//extensions
		$this->lessMap['djmegamenu.less'] = $common_ltr;
		$this->lessMap['djmegamenu_rtl.less'] = $common_rtl;
		
		$this->lessMap['djclassifieds.less'] = $common_ltr;
		$this->lessMap['djclassifieds_rtl.less'] = $common_rtl;
		$this->lessMap['djclassifieds_responsive.less'] = $common_ltr;

		// ---------------------------------------------------------
		// LESS VARIABLES
		// ---------------------------------------------------------

		$bootstrap_vars = array();
		
		/* Template Layout */
		//$parametr = $this->params->get('parametr', $this->defaults->get('parametr'));
		
		$templatefluidwidth = $this->params->get('JMfluidGridContainerLg', $this->defaults->get('JMfluidGridContainerLg'));
		$bootstrap_vars['JMfluidGridContainerLg'] = $templatefluidwidth;
		
		//check type
		$checkwidthtype = strstr($templatefluidwidth, '%');
		$checkwidthtypevalue = ($checkwidthtype) ? 'fluid' : 'fixed';
		$bootstrap_vars['JMtemplateWidthType'] = $checkwidthtypevalue;
		$templatewidthtype = $this->params->set('JMtemplateWidthType', $checkwidthtypevalue);
		
		$gutterwidth = $this->params->get('JMbaseSpace', $this->defaults->get('JMbaseSpace'));
		$bootstrap_vars['JMbaseSpace'] = $gutterwidth;
		
		//offcanvas
		$offcanvaswidth = $this->params->get('JMoffCanvasWidth', $this->defaults->get('JMoffCanvasWidth'));
		$bootstrap_vars['JMoffCanvasWidth'] = $offcanvaswidth;

        /* Font Modifications */
        
        //body
        
        $bodyfontsize = (int)$this->params->get('JMbaseFontSize', $this->defaults->get('JMbaseFontSize'));
		$bootstrap_vars['JMbaseFontSize'] = $bodyfontsize.'px';
		
        $bodyfonttype = $this->params->get('bodyFontType', '1');
        $bodyfontfamily = $this->params->get('bodyFontFamily', $this->defaults->get('bodyFontFamily')); 
        $bodygooglewebfontfamily = $this->params->get("bodyGoogleWebFontFamily", $this->defaults->get('bodyGoogleWebFontFamily'));
		$bodygooglewebfonturl = $this->params->get('bodyGoogleWebFontUrl');
        $generatedwebfontfamily = $this->params->get('bodyGeneratedWebFont');

        switch($bodyfonttype) {
            case "0" : {
                $bootstrap_vars['JMbaseFontFamily'] = $bodyfontfamily;
                break;    
            }
        	case "1" :{
                $bootstrap_vars['JMbaseFontFamily'] = $bodygooglewebfontfamily;
                break;
            }
            case "2" :{
            	$bootstrap_vars['JMbaseFontFamily'] = $generatedwebfontfamily;
            	break;
            }
            default: {
                $bootstrap_vars['JMbaseFontFamily'] = $this->defaults->get('bodyGoogleWebFontFamily');
                break;
            }
       	}
	   
		//top menu horizontal
		
		$djmenufontsize = (int)$this->params->get('JMtopmenuFontSize', $this->defaults->get('JMtopmenuFontSize'));
		$bootstrap_vars['JMtopmenuFontSize'] = $djmenufontsize.'px';
		
		$djmenufonttype = $this->params->get('djmenuFontType', '1');
		$djmenufontfamily = $this->params->get('djmenuFontFamily', $this->defaults->get('djmenuFontFamily'));
		$djmenugooglewebfontfamily = $this->params->get("djmenuGoogleWebFontFamily", $this->defaults->get('djmenuGoogleWebFontFamily'));
		$djmenugeneratedwebfontfamily = $this->params->get('djmenuGeneratedWebFont');
		
        switch($djmenufonttype) {
            case "0" : {
                $bootstrap_vars['JMtopmenuFontFamily'] = $djmenufontfamily;
                break;    
            }
            case "1" :{
                $bootstrap_vars['JMtopmenuFontFamily'] = $djmenugooglewebfontfamily;
                break;
            }
            case "2" :{
            	$bootstrap_vars['JMtopmenuFontFamily'] = $djmenugeneratedwebfontfamily;
            	break;
            }
            default: {
                $bootstrap_vars['JMtopmenuFontFamily'] = $this->defaults->get('djmenuGoogleWebFontFamily');
                break;
            }
       	}
       	
       	//module title
	   	
	 	$headingsfontsize = (int)$this->params->get('JMmoduleTitleFontSize', $this->defaults->get('JMmoduleTitleFontSize'));
		$bootstrap_vars['JMmoduleTitleFontSize'] = $headingsfontsize.'px';
		
		$headingsfonttype = $this->params->get('headingsFontType', '1');
		$headingsfontfamily = $this->params->get('headingsFontFamily', $this->defaults->get('headingsFontFamily')); 
		$headingsgooglewebfontfamily = $this->params->get("headingsGoogleWebFontFamily", $this->defaults->get('headingsGoogleWebFontFamily'));
		$headingsgeneratedwebfontfamily = $this->params->get('headingsGeneratedWebFont');
		
        switch($headingsfonttype) {
            case "0" : {
                $bootstrap_vars['JMmoduleTitleFontFamily'] = $headingsfontfamily;
                break;    
            }
            case "1" :{
                $bootstrap_vars['JMmoduleTitleFontFamily'] = $headingsgooglewebfontfamily;
                break;
            }
            case "2" :{
            	$bootstrap_vars['JMmoduleTitleFontFamily'] = $headingsgeneratedwebfontfamily;
            	break;
            }
            default: {
                $bootstrap_vars['JMmoduleTitleFontFamily'] = $this->defaults->get('headingsGoogleWebFontFamily');
                break;
            }
       	}
		
       	//article title
		
		$articlesfontsize = (int)$this->params->get('JMarticleTitleFontSize', $this->defaults->get('JMarticleTitleFontSize'));
		$bootstrap_vars['JMarticleTitleFontSize'] = $articlesfontsize.'px';
		
		$articlesfonttype = $this->params->get('articlesFontType', '1');
		$articlesfontfamily = $this->params->get('articlesFontFamily', $this->defaults->get('articlesFontFamily'));
		$articlesgooglewebfontfamily = $this->params->get("articlesGoogleWebFontFamily", $this->defaults->get('articlesGoogleWebFontFamily'));
		$articlesgeneratedfontfamily = $this->params->get('articlesGeneratedWebFont');
		
        switch($articlesfonttype) {
            case "0" : {
                $bootstrap_vars['JMarticleTitleFontFamily'] = $articlesfontfamily;
                break;    
            }
            case "1" :{
                $bootstrap_vars['JMarticleTitleFontFamily'] = $articlesgooglewebfontfamily;
                break;
            }
            case "2" :{
            	$bootstrap_vars['JMarticleTitleFontFamily'] = $articlesgeneratedfontfamily;
            	break;
            }
            default: {
                $bootstrap_vars['JMarticleTitleFontFamily'] = $this->defaults->get('articlesGoogleWebFontFamily');
                break;
            }
       	}
		
	    /* Color Modifications */
	    
	    //scheme color
        $colorversion = $this->params->get('JMcolorVersion', $this->defaults->get('JMcolorVersion')); 
		$bootstrap_vars['JMcolorVersion'] = $colorversion;

		//scheme images directory
		$imagesdir = $this->params->get('JMimagesDir', 'scheme1');
		$bootstrap_vars['JMimagesDir'] = $imagesdir;

		//custom variables
		
		// -------------------------------------
		// global
		// -------------------------------------
		
		//page background
		$JMallpageBackground = $this->params->get('JMallpageBackground', $this->defaults->get('JMallpageBackground')); 
		$bootstrap_vars['JMallpageBackground'] = $JMallpageBackground;
		
		//base font color
		$JMbaseFontColor = $this->params->get('JMbaseFontColor', $this->defaults->get('JMbaseFontColor')); 
		$bootstrap_vars['JMbaseFontColor'] = $JMbaseFontColor;
		
		//border
		$JMborder = $this->params->get('JMborder', $this->defaults->get('JMborder')); 
		$bootstrap_vars['JMborder'] = $JMborder;
		
		//headings
		$JMheadingColor = $this->params->get('JMheadingColor', $this->defaults->get('JMheadingColor')); 
		$bootstrap_vars['JMheadingColor'] = $JMheadingColor;
		
		// -------------------------------------
		// topbar
		// -------------------------------------
		
		//background
		$JMtopbarBackground = $this->params->get('JMtopbarBackground', $this->defaults->get('JMtopbarBackground')); 
		$bootstrap_vars['JMtopbarBackground'] = $JMtopbarBackground;
		
		//font color
		$JMtopbarFontColor = $this->params->get('JMtopbarFontColor', $this->defaults->get('JMtopbarFontColor')); 
		$bootstrap_vars['JMtopbarFontColor'] = $JMtopbarFontColor;
		
		//menu background
		$JMmenuTopbarBackgroundColor = $this->params->get('JMmenuTopbarBackgroundColor', $this->defaults->get('JMmenuTopbarBackgroundColor')); 
		$bootstrap_vars['JMmenuTopbarBackgroundColor'] = $JMmenuTopbarBackgroundColor;
		
		// -------------------------------------
		// header
		// -------------------------------------
		
		//modules background
		$JMheaderBackground = $this->params->get('JMheaderBackground', $this->defaults->get('JMheaderBackground')); 
		$bootstrap_vars['JMheaderBackground'] = $JMheaderBackground;
		
		//header module font color
		$JMheaderFontColor = $this->params->get('JMheaderFontColor', $this->defaults->get('JMheaderFontColor')); 
		$bootstrap_vars['JMheaderFontColor'] = $JMheaderFontColor;
		
		//module title font color
		$JMheaderModuleTitle = $this->params->get('JMheaderModuleTitle', $this->defaults->get('JMheaderModuleTitle')); 
		$bootstrap_vars['JMheaderModuleTitle'] = $JMheaderModuleTitle;
		
		// -------------------------------------
		// top menu
		// -------------------------------------
		
		//background
		$JMmegamenuBackground = $this->params->get('JMmegamenuBackground', $this->defaults->get('JMmegamenuBackground')); 
		$bootstrap_vars['JMmegamenuBackground'] = $JMmegamenuBackground;
		
		//border
		$JMmegamenuBorder = $this->params->get('JMmegamenuBorder', $this->defaults->get('JMmegamenuBorder')); 
		$bootstrap_vars['JMmegamenuBorder'] = $JMmegamenuBorder;
		
		//font color
		$JMmegamenuFontColor = $this->params->get('JMmegamenuFontColor', $this->defaults->get('JMmegamenuFontColor')); 
		$bootstrap_vars['JMmegamenuFontColor'] = $JMmegamenuFontColor;		

		// -------------------------------------
		// top mod
		// -------------------------------------
		
		//background
		$JMmiddleBackground = $this->params->get('JMmiddleBackground', $this->defaults->get('JMmiddleBackground')); 
		$bootstrap_vars['JMmiddleBackground'] = $JMmiddleBackground;
		
		//border
		$JMmiddleBorder = $this->params->get('JMmiddleBorder', $this->defaults->get('JMmiddleBorder')); 
		$bootstrap_vars['JMmiddleBorder'] = $JMmiddleBorder;
		
		//font color
		$JMmiddleFontColor = $this->params->get('JMmiddleFontColor', $this->defaults->get('JMmiddleFontColor')); 
		$bootstrap_vars['JMmiddleFontColor'] = $JMmiddleFontColor;
		
		//module title font color
		$JMtopmodModuleTitle = $this->params->get('JMtopmodModuleTitle', $this->defaults->get('JMtopmodModuleTitle')); 
		$bootstrap_vars['JMtopmodModuleTitle'] = $JMtopmodModuleTitle;

		// -------------------------------------
		// bottom3
		// -------------------------------------

		//font color
		$JMmoduleBottomTextColor = $this->params->get('JMmoduleBottomTextColor', $this->defaults->get('JMmoduleBottomTextColor')); 
		$bootstrap_vars['JMmoduleBottomTextColor'] = $JMmoduleBottomTextColor;
		
		//module title font color
		$JMbottom3ModuleTitle = $this->params->get('JMbottom3ModuleTitle', $this->defaults->get('JMbottom3ModuleTitle')); 
		$bootstrap_vars['JMbottom3ModuleTitle'] = $JMbottom3ModuleTitle;
		
		// -------------------------------------
		// footer modules
		// -------------------------------------
		
		//modules background
		$JMfootermodBackground = $this->params->get('JMfootermodBackground', $this->defaults->get('JMfootermodBackground')); 
		$bootstrap_vars['JMfootermodBackground'] = $JMfootermodBackground;
		
		//footer module font color
		$JMfootermodFontColor = $this->params->get('JMfootermodFontColor', $this->defaults->get('JMfootermodFontColor')); 
		$bootstrap_vars['JMfootermodFontColor'] = $JMfootermodFontColor;
		
		//module title font color
		$JMfootermodModuleTitle = $this->params->get('JMfootermodModuleTitle', $this->defaults->get('JMfootermodModuleTitle')); 
		$bootstrap_vars['JMfootermodModuleTitle'] = $JMfootermodModuleTitle;

		// -------------------------------------
		// modules
		// -------------------------------------
		
		//module title
		$JMmoduleTitleColor = $this->params->get('JMmoduleTitleColor', $this->defaults->get('JMmoduleTitleColor')); 
		$bootstrap_vars['JMmoduleTitleColor'] = $JMmoduleTitleColor;
		
		//module title borders
		$JMtitleBorderModuleBorderColor = $this->params->get('JMtitleBorderModuleBorderColor', $this->defaults->get('JMtitleBorderModuleBorderColor')); 
		$bootstrap_vars['JMtitleBorderModuleBorderColor'] = $JMtitleBorderModuleBorderColor;
		
		//white-ms font color
		$JMwhiteModuleFontColor = $this->params->get('JMwhiteModuleFontColor', $this->defaults->get('JMwhiteModuleFontColor')); 
		$bootstrap_vars['JMwhiteModuleFontColor'] = $JMwhiteModuleFontColor;
		
		//white-ms module title
		$JMwhiteModuleTitleColor = $this->params->get('JMwhiteModuleTitleColor', $this->defaults->get('JMwhiteModuleTitleColor')); 
		
		//white-ms background
		$JMwhiteModuleBackground = $this->params->get('JMwhiteModuleBackground', $this->defaults->get('JMwhiteModuleBackground')); 
		$bootstrap_vars['JMwhiteModuleBackground'] = $JMwhiteModuleBackground;
		
		//white-ms border
		$JMwhiteModuleBorderColor = $this->params->get('JMwhiteModuleBorderColor', $this->defaults->get('JMwhiteModuleBorderColor')); 
		$bootstrap_vars['JMwhiteModuleBorderColor'] = $JMwhiteModuleBorderColor;

		// -------------------------------------
		// extensions
		// -------------------------------------
		
		//MEDIATOOLS
		//title font color
		$JMmediatoolsTitleFontColor = $this->params->get('JMmediatoolsTitleFontColor', $this->defaults->get('JMmediatoolsTitleFontColor')); 
		$bootstrap_vars['JMmediatoolsTitleFontColor'] = $JMmediatoolsTitleFontColor;
		
		//description background
		$JMmediatoolsDescriptionBackgroundColor = $this->params->get('JMmediatoolsDescriptionBackgroundColor', $this->defaults->get('JMmediatoolsDescriptionBackgroundColor')); 
		$bootstrap_vars['JMmediatoolsDescriptionBackgroundColor'] = $JMmediatoolsDescriptionBackgroundColor;
		
		//description font color
		$JMmediatoolsDescriptionFontColor = $this->params->get('JMmediatoolsDescriptionFontColor', $this->defaults->get('JMmediatoolsDescriptionFontColor')); 
		$bootstrap_vars['JMmediatoolsDescriptionFontColor'] = $JMmediatoolsDescriptionFontColor;

		//CLASSIFIEDS
		//box background
		$JMclassifiedsBoxBackground = $this->params->get('JMclassifiedsBoxBackground', $this->defaults->get('JMclassifiedsBoxBackground')); 
		$bootstrap_vars['JMclassifiedsBoxBackground'] = $JMclassifiedsBoxBackground;

		//box border
		$JMclassifiedsBoxBorder = $this->params->get('JMclassifiedsBoxBorder', $this->defaults->get('JMclassifiedsBoxBorder')); 
		$bootstrap_vars['JMclassifiedsBoxBorder'] = $JMclassifiedsBoxBorder;

		//box title font color
		$JMclassifiedsBoxTitle = $this->params->get('JMclassifiedsBoxTitle', $this->defaults->get('JMclassifiedsBoxTitle')); 
		$bootstrap_vars['JMclassifiedsBoxTitle'] = $JMclassifiedsBoxTitle;
		
		//box text color
		$JMclassifiedsBoxText = $this->params->get('JMclassifiedsBoxText', $this->defaults->get('JMclassifiedsBoxText')); 
		$bootstrap_vars['JMclassifiedsBoxText'] = $JMclassifiedsBoxText;

		// -------------------------------------
		// offcanvas
		// -------------------------------------
        $offcanvasbackground = $this->params->get('JMoffCanvasBackground', $this->defaults->get('JMoffCanvasBackground')); 
		$bootstrap_vars['JMoffCanvasBackground'] = $offcanvasbackground;
		
        $offcanvasfontcolor = $this->params->get('JMoffCanvasFontColor', $this->defaults->get('JMoffCanvasFontColor')); 
		$bootstrap_vars['JMoffCanvasFontColor'] = $offcanvasfontcolor;	

		// -------------------------------------
		// end
		// -------------------------------------
       	$this->params->set('jm_bootstrap_variables', $bootstrap_vars);
	
		// -------------------------------------
		// compile LESS
		// -------------------------------------

		// Offline Page
		$this->CompileStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/offline.less'), true);
		
		// DJ-Classifieds
		$djclassifieds_theme = $this->CompileStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/djclassifieds.less'), true, true);
		$djclassifieds_theme_rtl = $this->CompileStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/djclassifieds_rtl.less'), true, true);
		$djclassifieds_responsive = $this->CompileStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/djclassifieds_responsive.less'), true, true);
		
		// DJ-Megamenu
		$djmegamenu_theme = $this->CompileStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/djmegamenu.less'), true, true);
		$djmegamenu_theme_rtl = $this->CompileStyleSheet(JPath::clean(JMF_TPL_PATH.'/less/djmegamenu_rtl.less'), true, true);
		
		// -------------------------------------
		// extensions themes
		// -------------------------------------	

        $app = JFactory::getApplication();		
		$themer = (int)$this->params->get('themermode', 0) == 1 ? true : false;
        if ($themer) { // add LESS files when Theme Customizer enabled
                
            $urlsToRemove = array(
            'templates/'.$app->getTemplate().'/css/djmegamenu.css' => array('url' => 'templates/'.$app->getTemplate().'/less/djmegamenu.less', 'type' => 'less'),
			'templates/'.$app->getTemplate().'/css/djmegamenu_rtl.css' => array('url' => 'templates/'.$app->getTemplate().'/less/djmegamenu_rtl.less', 'type' => 'less'),
            'components/com_djclassifieds/themes/'.$app->getTemplate().'/css/style.css' => array('url' => 'templates/'.$app->getTemplate().'/less/djclassifieds.less', 'type' => 'less'),
            'components/com_djclassifieds/themes/'.$app->getTemplate().'/css/style_rtl.css' => array('url' => 'templates/'.$app->getTemplate().'/less/djclassifieds_rtl.less', 'type' => 'less'),
            'components/com_djclassifieds/themes/'.$app->getTemplate().'/css/responsive.css' => array('url' => 'templates/'.$app->getTemplate().'/less/djclassifieds_responsive.less', 'type' => 'less')
            );
            $app->set('jm_remove_stylesheets', $urlsToRemove);
        } else { // add CSS files when Theme Customizer disabled 
            $urlsToRemove = array(
            'templates/'.$app->getTemplate().'/css/djmegamenu.css' => array('url' => $djmegamenu_theme, 'type' => 'css'),
			'templates/'.$app->getTemplate().'/css/djmegamenu_rtl.css' => array('url' => $djmegamenu_theme_rtl, 'type' => 'css'),
            'components/com_djclassifieds/themes/'.$app->getTemplate().'/css/style.css' => array('url' => $djclassifieds_theme, 'type' => 'css'),
            'components/com_djclassifieds/themes/'.$app->getTemplate().'/css/style_rtl.css' => array('url' => $djclassifieds_theme_rtl, 'type' => 'css'),
            'components/com_djclassifieds/themes/'.$app->getTemplate().'/css/responsive.css' => array('url' => $djclassifieds_responsive, 'type' => 'css')
            );
            $app->set('jm_remove_stylesheets', $urlsToRemove);
        }
    }
}