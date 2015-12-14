<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage 	DJ Classifieds Component
* @copyright 	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license 		http://www.gnu.org/licenses GNU/GPL
* @author 		url: http://design-joomla.eu
* @author 		email contact@design-joomla.eu
* @developer 	Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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

?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype='multipart/form-data'>
			<div class="row-fluid">
			<div class="span12 form-horizontal">
			<fieldset class="adminform">	
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#details" data-toggle="tab"><?php echo empty($this->day->id) ? JText::_('COM_DJCLASSIFIEDS_NEW') : JText::_('COM_DJCLASSIFIEDS_EDIT'); ?></a>
					</li>
					<li >
						<a href="#img_prices" data-toggle="tab"><?php echo JText::_('COM_DJCLASSIFIEDS_IMAGES_PRICES'); ?></a>
					</li>
					<li >
						<a href="#char_prices" data-toggle="tab"><?php echo JText::_('COM_DJCLASSIFIEDS_CHARS_PRICES'); ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_DAYS');?><br /><span style="color:#666;font-size:11px;"><?php echo JText::_('COM_DJCLASSIFIEDS_0_FOR_UNLIMITED');?></span></div>
							<div class="controls">
	                    		<input class="text_area" type="text" name="days" id="days" size="20" maxlength="250" value="<?php echo $this->day->days; ?>" />	                									
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE');?><br />(11.22)</div>
							<div class="controls">
								<input class="text_area" type="text" name="price" id="price" size="20" maxlength="250" <?php if($this->day->id>0 && $this->day->price==0){ echo 'readonly="true"'; }?>
									value="<?php echo $this->day->price; ?>" />
								<input onchange="freeprice();" type="checkbox" value="1" name="price_free" id="price_free" <?php if($this->day->id>0 && $this->day->price==0){ echo 'checked'; }?> />
								<span style="margin-top:3px;display:inline-block;"><?php echo JText::_('COM_DJCLASSIFIEDS_FREE');?></span>	                									
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE_POINTS');?></div>
							<div class="controls">
	                			<input class="text_area" type="text" name="points" id="points" size="20" maxlength="250" value="<?php echo $this->day->points; ?>" <?php if($this->day->id>0 && $this->day->price==0){ echo 'readonly="true"'; }?> />						
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_RENEW_PRICE');?><br />(11.22)</div>
							<div class="controls">
								<input class="text_area" type="text" name="price_renew" id="price_renew" size="20" maxlength="250" <?php if($this->day->id>0 && $this->day->price_renew==0){ echo 'readonly="true"'; }?>
								value="<?php echo $this->day->price_renew; ?>" />
								<input onchange="freepricerenew();" type="checkbox" value="1" name="price_renew_free" id="price_renew_free" <?php if($this->day->id>0 && $this->day->price_renew==0){ echo 'checked'; }?> />
								<span style="margin-top:3px;display:inline-block;"><?php echo JText::_('COM_DJCLASSIFIEDS_FREE');?></span>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_RENEW_PRICE_POINTS');?></div>
							<div class="controls">
								<input class="text_area" type="text" name="points_renew" id="points_renew" size="20" maxlength="250" value="<?php echo $this->day->points_renew; ?>" <?php if($this->day->id>0 && $this->day->price_renew==0){ echo 'readonly="true"'; }?> />	                									
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PUBLISHED');?></div>
							<div class="controls">
								<input autocomplete="off" type="radio" name="published" value="1" <?php  if($this->day->published==1 || $this->day->id==0){echo "checked";}?> /><span style="float:left; margin:2px 10px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
								<input autocomplete="off" type="radio" name="published" value="0" <?php  if($this->day->published==0 && $this->day->id>0){echo "checked";}?> /><span style="float:left; margin:2px 10px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span>	                									
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"></div>
							<div class="controls">
	                									
							</div>
						</div>						
					</div>

					
					
					<div class="tab-pane" id="img_prices">																		
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_DEFAULT_FROM_PARAMETERS');?></div>
							<div class="controls">
								<input onchange="imgdefaultprice();" type="checkbox" value="1" name="img_price_default" id="img_price_default" <?php if($this->day->id==0 || $this->day->img_price_default==1){ echo 'checked'; }?> />
							</div>
						</div>								
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE');?><br />(11.22)</div>
							<div class="controls">
								<input class="text_area" type="text" name="img_price" id="img_price" size="20" maxlength="250" <?php if($this->day->id==0 || $this->day->img_price_default==1){ echo 'readonly="true"'; }?>
									value="<?php echo $this->day->img_price; ?>" />	                									
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE_POINTS');?></div>
							<div class="controls">
	                			<input class="text_area" type="text" name="img_points" id="img_points" size="20" maxlength="250" value="<?php echo $this->day->img_points; ?>" <?php if($this->day->id==0 || $this->day->img_price_default==1){ echo 'readonly="true"'; }?> />						
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_RENEW_PRICE');?><br />(11.22)</div>
							<div class="controls">
								<input class="text_area" type="text" name="img_price_renew" id="img_price_renew" size="20" maxlength="250" <?php if($this->day->id==0 || $this->day->img_price_default==1){ echo 'readonly="true"'; }?>
								value="<?php echo $this->day->img_price_renew; ?>" />
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_RENEW_PRICE_POINTS');?></div>
							<div class="controls">
								<input class="text_area" type="text" name="img_points_renew" id="img_points_renew" size="20" maxlength="250" value="<?php echo $this->day->img_points_renew; ?>" <?php if($this->day->id==0 || $this->day->img_price_default==1){ echo 'readonly="true"'; }?> />	                									
							</div>
						</div>						
					</div>
					
					
					<div class="tab-pane" id="char_prices">																		
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_DEFAULT_FROM_PARAMETERS');?></div>
							<div class="controls">
								<input onchange="chardefaultprice();" type="checkbox" value="1" name="char_price_default" id="char_price_default" <?php if($this->day->id==0 || $this->day->char_price_default==1){ echo 'checked'; }?> />
							</div>
						</div>								
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE');?><br />(11.22)</div>
							<div class="controls">
								<input class="text_area" type="text" name="char_price" id="char_price" size="20" maxlength="250" <?php if($this->day->id==0 || $this->day->char_price_default==1){ echo 'readonly="true"'; }?>
									value="<?php echo $this->day->char_price; ?>" />	                									
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE_POINTS');?></div>
							<div class="controls">
	                			<input class="text_area" type="text" name="char_points" id="char_points" size="20" maxlength="250" value="<?php echo $this->day->char_points; ?>" <?php if($this->day->id==0 || $this->day->char_price_default==1){ echo 'readonly="true"'; }?> />						
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_RENEW_PRICE');?><br />(11.22)</div>
							<div class="controls">
								<input class="text_area" type="text" name="char_price_renew" id="char_price_renew" size="20" maxlength="250" <?php if($this->day->id==0 || $this->day->char_price_default==1){ echo 'readonly="true"'; }?>
								value="<?php echo $this->day->char_price_renew; ?>" />
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_RENEW_PRICE_POINTS');?></div>
							<div class="controls">
								<input class="text_area" type="text" name="char_points_renew" id="char_points_renew" size="20" maxlength="250" value="<?php echo $this->day->char_points_renew; ?>" <?php if($this->day->id==0 || $this->day->char_price_default==1){ echo 'readonly="true"'; }?> />	                									
							</div>
						</div>						
					</div>
				</div>
			</fieldset>
			</div>
			</div>
			<input type="hidden" name="id" value="<?php echo $this->day->id; ?>" />
			<input type="hidden" name="option" value="com_djclassifieds" />
			<input type="hidden" name="task" value="day" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>		
	<script type="text/javascript">
	function freeprice(){
		if(document.getElementById('price_free').checked){
			document.getElementById("price").value="0";
			document.id('price').setProperty("readonly",true);
			document.getElementById("points").value="0";
			document.id('points').setProperty("readonly",true);
		}else{
			document.id('price').setProperty("readonly",false);
			document.id('points').setProperty("readonly",false)
		}
	}		
	
	function freepricerenew(){
		if(document.getElementById('price_renew_free').checked){
			document.getElementById("price_renew").value="0";
			document.id('price_renew').setProperty("readonly",true);
			document.getElementById("points_renew").value="0";
			document.id('points_renew').setProperty("readonly",true);
		}else{
			document.id('price_renew').setProperty("readonly",false);
			document.id('points_renew').setProperty("readonly",false);
		}
	}

	function imgdefaultprice(){
		if(document.getElementById('img_price_default').checked){
			document.getElementById("img_price").value="0";
			document.id('img_price').setProperty("readonly",true);
			document.getElementById("img_points").value="0";
			document.id('img_points').setProperty("readonly",true);
			document.getElementById("img_points").value="0";
			document.id('img_price_renew').setProperty("readonly",true);
			document.getElementById("img_price_renew").value="0";
			document.id('img_points_renew').setProperty("readonly",true);
			document.getElementById("img_points_renew").value="0";
		}else{
			document.id('img_price').setProperty("readonly",false);
			document.id('img_points').setProperty("readonly",false);
			document.id('img_price_renew').setProperty("readonly",false);
			document.id('img_points_renew').setProperty("readonly",false);
		}
	}

	function chardefaultprice(){
		if(document.getElementById('char_price_default').checked){
			document.getElementById("char_price").value="0";
			document.id('char_price').setProperty("readonly",true);
			document.getElementById("char_points").value="0";
			document.id('char_points').setProperty("readonly",true);
			document.getElementById("char_points").value="0";
			document.id('char_price_renew').setProperty("readonly",true);
			document.getElementById("char_price_renew").value="0";
			document.id('char_points_renew').setProperty("readonly",true);
			document.getElementById("char_points_renew").value="0";
		}else{
			document.id('char_price').setProperty("readonly",false);
			document.id('char_points').setProperty("readonly",false);
			document.id('char_price_renew').setProperty("readonly",false);
			document.id('char_points_renew').setProperty("readonly",false);
		}
	}
	</script>
<?php echo DJCFFOOTER; ?>