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
JHTML::_('behavior.framework');
JHTML::_('behavior.formvalidation');
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$app = JFactory::getApplication();
$config = JFactory::getConfig();
$user =  JFactory::getUser();
$document= JFactory::getDocument();
$session = JFactory::getSession();
require_once(JPATH_COMPONENT.DS.'assets'.DS.'recaptchalib.php');
$publickey = $par->get('captcha_publickey',"6LfzhgkAAAAAAL9RlsE0x-hR2H43IgOFfrt0BxI0");
$privatekey = $par->get('captcha_privatekey',"6LfzhgkAAAAAAOJNzAjPz3vXlX-Bw0l-sqDgipgs");
$error='';
$Itemid = JRequest::getVar('Itemid', 0,'', 'int');
$item = $this->item;

?>
	<div class="row_gd user_name">
		<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_CREATED_BY'); ?></span>
			<div class="row_value">
				<?php 
				if($item->user_id==0){
					echo JText::_('COM_DJCLASSIFIEDS_GUEST');
				}else{
					$uid_slug = $item->user_id.':'.DJClassifiedsSEO::getAliasName($item->username);
					?>
					<div class="profile_item_box">
						<?php 							
							echo '<a class="profile_img" href="index.php?option=com_djclassifieds&view=profile&uid='.$uid_slug.DJClassifiedsSEO::getMainAdvertsItemid().'">';
								if($par->get('profile_avatar_source','')){
									echo DJClassifiedsSocial::getUserAvatar($item->user_id,$par->get('profile_avatar_source',''),'S');
								}else{
									if($this->profile['img']){
										echo '<img src="'.JURI::base(true).$this->profile['img']->path.$this->profile['img']->name.'_ths.'.$this->profile['img']->ext.'" />';
									}else{
										echo '<img style="width:'.$par->get('prof_smallth_width','50').'px" src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/default_profile_s.png" />';	
									}									
								}
							echo '</a>';
						?>
						<div class="profile_name_data">
							<?php echo '<a class="profile_name" href="index.php?option=com_djclassifieds&view=profile&uid='.$uid_slug.DJClassifiedsSEO::getMainAdvertsItemid().'">'.$item->username.' <span>('.$this->user_items_c.')</span></a>'; ?>
							<?php if($this->profile['data']){ ?>
								<div class="profile_data">
								<?php foreach($this->profile['data'] as $f){
									if($par->get('show_empty_cf','1')==0){
										if(!$f->value && ($f->value_date=='' || $f->value_date=='0000-00-00')){
											continue;
										}
									}
									?>
									<div class="profile_row row_<?php echo $f->name;?>">
										<span class="profile_row_label"><?php echo JText::_($f->label); ?>: </span>
										<span class="profile_row_value">
											<?php 
											if($f->type=='textarea'){							
												if($f->value==''){echo '---'; }
												else{echo $f->value;}								
											}else if($f->type=='checkbox'){
												if($f->value==''){echo '---'; }
												else{
													echo str_ireplace(';', ', ', substr($f->value,1,-1));
												}
											}else if($f->type=='date'){
												if($f->value_date==''){echo '---'; }
												else{
													echo $f->value_date;
												}
											}else if($f->type=='link'){
												if($f->value==''){echo '---'; }
												else{
													if(strstr($f->value, 'http://') || strstr($f->value, 'https://')){
														echo '<a '.$f->params.' href="'.$f->value.'">'.str_ireplace(array("http://","https://"), array('',''), $f->value).'</a>';;
													}else{
														echo '<a '.$f->params.' href="http://'.$f->value.'">'.$f->value.'</a>';;
													}																
												}							
											}else{
												if($f->value==''){echo '---'; }
												else{echo $f->value;}	
											}
											?>
										</span>
									</div>		
								<?php }?>
								</div> 
						<?php }?>
						<?php if($par->get('profile_social_link','')){ ?>
							<div class="profile_adverts_link">
								<a href="index.php?option=com_djclassifieds&view=profile&uid=<?php echo $uid_slug.DJClassifiedsSEO::getMainAdvertsItemid(); ?>" alt="" >
									<?php echo JText::_('COM_DJCLASSIFIEDS_VIEW_ALL_ADS'); ?>
								</a>
							</div>
							<div class="profile_social_link">
								<a href="<?php echo DJClassifiedsSocial::getUserProfileLink($item->user_id,$par->get('profile_social_link','')); ?>" alt="" >
									<?php echo JText::_('COM_DJCLASSIFIEDS_VISIT_SOCIAL_PROFILE'); ?>
								</a>
							</div>
						<?php }?>						
						</div>
					</div>
					<?php if($item->event->onAfterDJClassifiedsDisplayAdvertAuthor) { ?>
						<div class="djcf_after_author">
							<?php echo $this->item->event->onAfterDJClassifiedsDisplayAdvertAuthor; ?>
						</div>
					<?php } ?>				 	
					<?php 										 
				}?>
			</div>
		</div>
<?php 		