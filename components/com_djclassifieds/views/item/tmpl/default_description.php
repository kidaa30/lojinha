<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
* @copyright	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license		http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
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

defined ('_JEXEC') or die('Restricted access');
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$app = JFactory::getApplication();
$item = $this->item;
$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';
if($item->description || $par->get('intro_desc_in_advert','0') || isset($item->tabs) ){

	if($this->item->event->beforeDJClassifiedsDisplayContent) { ?>
		<div class="djcf_before_desc">
			<?php echo $item->event->beforeDJClassifiedsDisplayContent; ?>
		</div>
	<?php }

	//if(!$item->description){$item->description = $item->intro_desc;}
	echo '<div class="description"><h2>'.JText::_('COM_DJCLASSIFIEDS_DESCRIPTION').'</h2>';
		if($par->get('intro_desc_in_advert','0')){
			echo '<div class="intro_desc_content">'.$item->intro_desc.'</div>';
		}	
		if($item->description){
			echo '<div class="desc_content">';
				if($par->get('desc_plugins','')){
					echo JHTML::_('content.prepare',$item->description);
				}else{
					echo $item->description;
				}
			echo '</div>';
		}
		if (isset($this->item->tabs)) { ?>
           	<div class="djcf_tabs">
           		<?php echo JHTML::_('content.prepare', $this->item->tabs); ?>
           		<div class="clear_both"></div>
           	</div>
        <?php } 
		
	echo '</div>';	

	$modules_djcf = &JModuleHelper::getModules('djcf-item-description');
	if(count($modules_djcf)>0){
		echo '<div class="djcf-ad-item-description clearfix">';
		foreach (array_keys($modules_djcf) as $m){
			echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
		}
		echo'</div>';
	}
	if($this->item_payments==0){
		$modules_djcf = &JModuleHelper::getModules('djcf-item-description-free');
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-item-description clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';
		}	
	}
?>
<script type="text/javascript">
window.addEvent('load', function() {
	var djcfpagebreak_acc = new Fx.Accordion('.djcf_tabs .accordion-toggle',
			'.djcf_tabs .accordion-body', {
				alwaysHide : false,
				display : 0,
				duration : 100,
				onActive : function(toggler, element) {
					toggler.addClass('active');
					element.addClass('in');
				},
				onBackground : function(toggler, element) {
					toggler.removeClass('active');
					element.removeClass('in');
				}
			});
	var djcfpagebreak_tab = new Fx.Accordion('.djcf_tabs li.nav-toggler',
			'.djcf_tabs div.tab-pane', {
				alwaysHide : true,
				display : 0,
				duration : 150,
				onActive : function(toggler, element) {
					toggler.addClass('active');
					element.addClass('active');
				},
				onBackground : function(toggler, element) {
					toggler.removeClass('active');
					element.removeClass('active');
				}
			});
});
</script>

<?php } ?>