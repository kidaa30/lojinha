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
$document= JFactory::getDocument();
$config = JFactory::getConfig();
$item = $this->item;
if($par->get('show_googlemap')==1){
	if($config->get('force_ssl',0)==2){
		$document->addScript("https://maps.google.com/maps/api/js?sensor=false&amp;v=3.exp&amp;libraries=places");
	}else{
		$document->addScript("http://maps.google.com/maps/api/js?sensor=false&amp;v=3.exp&amp;libraries=places");
	}	
}

?>
<div class="localization_det<?php if(count($this->fields)==0){echo ' loc_det_wide';} ?>">
		<h2><?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'); ?></h2>
		<div class="row">
			<span class="row_value" style="display:inline-block;">
				<?php 
				echo $this->address.'<br />';
				echo $item->address;
				if($par->get('show_postcode','0')){
					if($item->post_code){
						echo ', '.$item->post_code;
					}				
				}
				?>
			</span>
		</div>
		<?php 
		if($par->get('show_googlemap')==1){ ?>
			<div id="google_map_box" style="display:none;">
				 <div id='map' style='width: 320px; height: 210px;'></div>
				 <?php if($par->get('show_lat_lng',0) && $item->latitude!='0.000000000000000' && $item->longitude!='0.000000000000000'){ ?>
				 	<div class="geo_coordinates"><?php echo JText::_('COM_DJCLASSIFIEDS_GEOGRAPHIC_COORDINATES')?>:<span>
				 		<?php
				 		 echo '(';
						 $latitude = explode('.',$this->item->latitude);
						 if(isset($latitude[1])){
						 	echo $latitude[0].'.'.rtrim($latitude[1],'0');
						 }else{
						 	echo $this->item->latitude;
						 }
						 echo ',';
						 $longitude = explode('.',$this->item->longitude);
						 if(isset($longitude[1])){
						 	echo $longitude[0].'.'.rtrim($longitude[1],'0');
						 }else{
						 	echo $this->item->longitude;
						 }
						 echo ')';?>
					</span></div>	
				 <?php } ?>					 
				 <div class="map_info"><?php echo JText::_('COM_DJCLASSIFIEDS_MAP_ACCURACY')?></div>
				<?php if($par->get('show_gm_driving')==1){ ?>
					<form action="<?php echo JURI::base();?>/index.php" method="post" class="gm_drive_dir" target="_blank">
						<label><?php echo JText::_('COM_DJCLASSIFIEDS_DRIVE_DIRECTIONS');?></label>
						<input type="hidden" name="option" value="com_djclassifieds" />
						<input type="hidden" name="view" value="item" />
						<input type="hidden" name="task" value="driveDirections" />
						<input type="hidden" name="id" value="<?php echo $item->id;?>" />
						<input type="text" class="inputbox" name="saddr" value="<?php echo JText::_('COM_DJCLASSIFIEDS_ENTER_ADDRESS');?>" onblur="if (this.value=='') this.value='<?php echo JText::_('COM_DJCLASSIFIEDS_ENTER_ADDRESS');?>';" onfocus="if(this.value=='<?php echo JText::_('COM_DJCLASSIFIEDS_ENTER_ADDRESS');?>') this.value='';" />
						<input class="button" type="submit" value="<?php echo JText::_('COM_DJCLASSIFIEDS_DIRECTIONS_SEARCH'); ?>" />
						<?php 								
							$item_address = $this->country;
							if($this->country){$item_address = $this->country;
							}else{$item_address = '';}								
							if($this->city!='' ){
								if($item_address){ $item_address .= ", ";	}
								$item_address .= $this->city;
							} 
							if($this->item->address!='' ){
								if($item_address){ $item_address .= ", ";	}
								$item_address .= $this->item->address;
							} 
						?>
						<input type="hidden" name="address" value="<?php echo $item_address;?>" />							
					</form>
					<?php 
						if(isset($_COOKIE["djcf_latlon"])) {														
							$saddr =  str_ireplace('_', ',', $_COOKIE["djcf_latlon"]);
							echo '<a class="gm_drive_dir_l" target="_blank" href="https://maps.google.com/maps?saddr='.$saddr.'&daddr='.$item_address.'">'.JText::_('COM_DJCLASSIFIEDS_OR_USE_LOCALIZATION').'<span></span></a>';
						}else{
							echo '<span class="gm_drive_dir_l"><button class="button" onclick="getDJDriveLocation()" >'.JText::_('COM_DJCLASSIFIEDS_OR_USE_LOCALIZATION').'</button><span></span></span>';
						}
					?>
					<script type="text/javascript">
						function getDJDriveLocation(){
						  if(navigator.geolocation){
							  navigator.geolocation.getCurrentPosition(showDJDrivePosition);
						   }else{
							   x.innerHTML="<?php echo JText::_('COM_DJCLASSIFIEDS_GEOLOCATION_IS_NOT_SUPPORTED_BY_THIS_BROWSER');?>";}
						 }
						function showDJDrivePosition(position){
						  	var exdate=new Date();
						  	exdate.setDate(exdate.getDate() + 1);
							var lc = position.coords.latitude+'_'+position.coords.longitude;
							var ll = position.coords.latitude+','+position.coords.longitude;
						  	document.cookie = "djcf_latlon=" + lc + "; expires=" + exdate.toUTCString();						  	
						  	window.open("http://maps.google.com/maps?saddr="+ll+"&daddr=<?php echo $item_address; ?>");						  							  				 
					  	}
					</script>
				<?php } ?>								      
			</div>	
		<?php }	?>		
	</div>
	<script type="text/javascript">
		<?php if($par->get('show_googlemap')==1){?>
		window.addEvent('load', function(){ 
			mapaStart();
		});
	<?php 
		$marker_txt = '<div style="width:200px"><div style="margin-bottom:5px;"><strong>'.$this->item->name.'</strong></div>';
		$marker_txt .= str_ireplace("\r\n", '<br />', $this->item->intro_desc).'<br />'; 
//		$marker_txt .= '<strong>'.JText::_('Type').'</strong> : '.$item->type_name.'<br />';
//		$marker_txt .= '<strong>'.JText::_('Price').'</strong> : '.$item->price.'<br />';
//		$marker_txt .= '<strong>'.JText::_('Address').'</strong> : '.$item->country.", ".$item->city.'<br />';
//		if($item->street!='' && $this->subsc==1){
//			$marker_txt .= $item->street.'<br />';
//		}

		$marker_txt .= '<div style="margin-top:10px;">';

		if(isset($this->item_images[0])){
			for($ii=0; $ii<count($this->item_images); $ii++){
				$marker_txt .= '<img width="60px" src="'.JURI::base(true).$this->item_images[$ii]->thumb_s.'" /> ';
				if($ii==3){
					break;
				}
			}
		}
		$marker_txt .='</div></div>';	

		?>
	        var map;
	        var map_marker = new google.maps.InfoWindow();
	        var geokoder = new google.maps.Geocoder();
	        
			function addMarker(position,txt,icon)
			{
			    var MarkerOpt =  
			    { 
			        position: position, 
			        icon: icon,	
			        map: map
			    } 
			    var marker = new google.maps.Marker(MarkerOpt);
			    marker.txt=txt;
			     
			    google.maps.event.addListener(marker,"click",function()
			    {
			        map_marker.setContent(marker.txt);
			        map_marker.open(map,marker);
			    });
			    return marker;
			}
			    	
			 function mapaStart()    
			 {   		 	 
	             <?php
	             	 $icon_img = ''; 
					 $icon_size='';
	             	 if($par->get('gm_icon',1)==1 && file_exists(JPATH_BASE.'/images/djcf_gmicon_'.$this->item->cat_id.'.png')){ 
	             		$icon_size = getimagesize(JPATH_BASE.'/images/djcf_gmicon_'.$this->item->cat_id.'.png');
	             		$icon_img = JURI::base().'images/djcf_gmicon_'.$this->item->cat_id.'.png';             		
	        		 }else if($par->get('gm_icon',1)==1 && file_exists(JPATH_BASE.'/images/djcf_gmicon.png')){
	        			 $icon_size = getimagesize(JPATH_BASE.'/images/djcf_gmicon.png');
	                	 $icon_img = JURI::base()."images/djcf_gmicon.png";
	                 }elseif($par->get('gm_icon',1)==1){ 
	                	 $icon_size = getimagesize(JPATH_BASE.'/components/com_djclassifieds/assets/images/djcf_gmicon.png');
	                	 $icon_img = JURI::base()."components/com_djclassifieds/assets/images/djcf_gmicon.png";
	                 }
	                 //$icon_img = ''; 
	                 if($icon_img && is_array($icon_size)){ 
	                 	 $anchor_w = $icon_size[0]/2;?>
			             var size = new google.maps.Size(<?php echo $icon_size[0].','.$icon_size[1];?>);
			             var start_point = new google.maps.Point(0,0);
			             var anchor_point = new google.maps.Point(<?php echo $anchor_w.','.$icon_size[1];?>);   
			             var icon = new google.maps.MarkerImage("<?php echo $icon_img;?>", size, start_point, anchor_point);                
	                <?php }else{ ?>
	              		 var icon = '';  	
	                <?php }?>
	                
	             	
				<?php if($this->item->latitude!='0.000000000000000' && $this->item->longitude!='0.000000000000000'){ ?>
					document.getElementById("google_map_box").style.display='block';
					var adLatlng = new google.maps.LatLng(<?php echo $this->item->latitude.','.$this->item->longitude; ?>);
					    var MapOptions = {
					       zoom: <?php echo $par->get('gm_zoom','10'); ?>,
					  		center: adLatlng,
					  		mapTypeId: google.maps.MapTypeId.<?php echo $par->get('gm_type','ROADMAP'); ?>,
					  		navigationControl: true
					    };
					    map = new google.maps.Map(document.getElementById("map"), MapOptions); 				   
				    	var marker = addMarker(adLatlng,'<?php echo addslashes($marker_txt); ?>',icon);
				<?php }else{ ?>
					var adres = '<?php echo $this->country;if($this->city!='' ){echo ", ".str_ireplace("'", "&apos;",$this->city);} if($this->item->address!='' ){echo ", ".str_ireplace("'", "&apos;",$this->item->address);}?>';
					geokoder.geocode({address: adres}, function (results, status)
					{
					    if(status == google.maps.GeocoderStatus.OK)
					    {
					    	document.getElementById("google_map_box").style.display='block';
						    var MapOptions = {
						       zoom: <?php echo $par->get('gm_zoom','10'); ?>,
						  		center: results[0].geometry.location,
						  		mapTypeId: google.maps.MapTypeId.ROADMAP,
						  		navigationControl: true
						    };
						    map = new google.maps.Map(document.getElementById("map"), MapOptions); 
					    	var marker = addMarker(results[0].geometry.location,'<?php echo addslashes($marker_txt); ?>',icon);
					    }
					});		
				<?php } ?>      
			 }

		<?php }?>
		</script>