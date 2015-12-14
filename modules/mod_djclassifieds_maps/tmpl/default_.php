<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Search Module
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
	/*$menus	= JSite::getMenu();	
	$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&cid=0',1);
	$menu_item_blog = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=blog&cid=0',1);
			
	$itemid = ''; 
	if($menu_item){
		$itemid='&Itemid='.$menu_item->id;
	}else if($menu_item_blog){
		$itemid='&Itemid='.$menu_item_blog->id;
	}	*/


	$document=& JFactory::getDocument();
	$document->addScript("http://maps.google.com/maps/api/js?sensor=false");				
	$document->addScript(JURI::base().'/components/com_djclassifieds/assets/fluster/Fluster2.packed.js');
	
$par = &JComponentHelper::getParams( 'com_djclassifieds' );
	if($par->get('gm_icon',1)==1 && file_exists(JPATH_BASE.'/images/djcf_gmicon.png')){
		$gm_icon = JURI::base().'images/djcf_gmicon.png';
    }elseif($par->get('gm_icon',1)==1){
    	$gm_icon = JURI::base().'components/com_djclassifieds/assets/images/djcf_gmicon.png'; 
    }else{ 
  		$gm_icon = '';  	
    }	
?>
<div class="dj_cf_maps">
	<div id="djmod_map_box" style="display:none;">
		 <div id='djmod_map' style='width: <?php echo $params->get('map_width');?>; height: <?php echo $params->get('map_height');?>; border: 1px solid #666; '>						  
		 </div>      
	</div>
</div>

<script type="text/javascript">


window.addEvent('domready', function(){
		djmodMapaStart();
});
	
<?php
/*
	$marker_txt = '<div style="width:200px"><div style="margin-bottom:5px;"><strong>'.$this->item->name.'</strong></div>';
	$marker_txt .= $this->item->intro_desc.'<br />'; 

	$marker_txt .= '<div style="margin-top:10px;">';
	

									
	if($this->item->image_url!=''){
		$images=explode(';', substr($this->item->image_url,0,-1));
		
		$path = str_replace('/administrator','',JURI::base());
		$path .= '/components/com_djclassifieds/images/';
		for($ii=0; $ii<count($images); $ii++){
			$marker_txt .= '<img width="60px" src="'.$path.$images[$ii].'.ths.jpg" /> ';
			if($ii==3){
				break;
			}
		}
	}
	$marker_txt .='</div></div>';	
*/
?>
         var djmod_map;
         var fluster ;
         var djmod_map_marker = new google.maps.InfoWindow();
         var djmod_geokoder = new google.maps.Geocoder();
         var items_address = new Array();
		 var items_desc = new Array();
         
		function djmodAddMarker(position,txt,icon)
		{			
		    var MarkerOptions =  
		    { 
		        position: position, 
		        icon: icon,	
		        map: djmod_map
		    } 
		    var marker = new google.maps.Marker(MarkerOptions);
		    marker.txt=txt;
		     
		    google.maps.event.addListener(marker,"click",function()
		    {
		        djmod_map_marker.setContent(marker.txt);
		        djmod_map_marker.open(djmod_map,marker);
		    });
		    return marker;
		}
		
		    	
		 function djmodMapaStart()    
		 {   		 

			djmod_geokoder.geocode({address: '<?php echo $params->get('start_address');?>'}, function (results, status)
			{
			    if(status == google.maps.GeocoderStatus.OK)
			    {			    
				 document.getElementById("djmod_map_box").style.display='block';
				    var opcjeMapy = {
				        zoom: <?php echo $params->get('start_zoom');?>,
				  		center: results[0].geometry.location,
				  		mapTypeId: google.maps.MapTypeId.ROADMAP,
				  		navigationControl: true
				    };
				    djmod_map = new google.maps.Map(document.getElementById("djmod_map"), opcjeMapy); 
				    fluster = new Fluster2(djmod_map);
					 var size = new google.maps.Size(32,32);
	                 var start_point = new google.maps.Point(0,0);
	                 var anchor_point = new google.maps.Point(0,16);
	            		<?php if($gm_icon){ ?>
		                	 var icon = new google.maps.MarkerImage("<?php echo $gm_icon;?>", size, start_point, anchor_point);
		                <?php }else{ ?>
		              		 var icon = '';  	
		                <?php }?>	 

					<?php 
					$ia=0;
					foreach($items as $item){						
						if(!$item->alias){
							$item->alias = DJClassifiedsSEO::getAliasName($item->name);
						}
						if(!$item->c_alias){
							$item->c_alias = DJClassifiedsSEO::getAliasName($item->c_name);					
						}
										
						?>
						
						items_address[<?php echo $ia; ?>]='<?php echo $item->country.", ".$item->city; if($item->address!='' ){echo ", ".$item->address;}?>';
							<?php								
				    			$marker_txt = '<div style="width:200px;margin-bottom:0px"><div style="margin-bottom:5px;">';
				    			$marker_txt .= '<a href="'.JRoute::_(DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias)).' "><strong>'.addslashes($item->name).'</strong></a></div>';
									$marker_txt .= addslashes(str_replace(array("\n", "\r"), '',$item->intro_desc)).'<br />'; 								
									$marker_txt .= '<div style="margin-top:10px;">';																																	
										if($item->image_url!=''){
											$images=explode(';', substr($item->image_url,0,-1));
											$path = JURI::base().'/components/com_djclassifieds/images/';
											for($ii=0; $ii<count($images); $ii++){
												$marker_txt .= '<img width="60px" src="'.$path.$images[$ii].'.ths.jpg" /> ';
												if($ii==3){
													break;
												}
											}
										}										
									$marker_txt .='</div></div>';										
				    			?>
				    		items_desc[<?php echo $ia; ?>]='<?php echo $marker_txt; ?>';
				    	<?php 
				    	$ia++;
				    		} ?>										
								for(var ic=0;ic<items_address.length;ic++){
									if(Math.floor(ic/10)==0){
										var icc = ((ic+1)*200) + (2000*Math.floor(ic/10));	
									}else{
										var icc = ((ic+1)*600) + (2000*Math.floor(ic/10));
									}
																																					
									setTimeout(function(ic){			
										djmod_geokoder.geocode({address: items_address[ic]}, function (results, status){
							    			if(status == google.maps.GeocoderStatus.OK){				    					    			
							    				//djmodAddMarker(results[0].geometry.location,items_desc[ic],icon);
							    				var marker = new google.maps.Marker({
													position: results[0].geometry.location,
													icon: icon,	
													title: 'Marker '
													
												});
												
												// Add the marker to the Fluster
												fluster.addMarker(marker);																							
											}else{
												console.log(status);
											}
													
										});									
									},icc,ic);
								
								}
								setTimeout(function(ic){
									console.log('initialize');
									fluster.initialize();	
								},icc);
																									    
			    	}
				
				
				
					// Set styles
	// These are the same styles as default, assignment is only for demonstration ...
	fluster.styles = {
		// This style will be used for clusters with more than 0 markers
		0: {
			image: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/images/m1.png',
			textColor: '#FFFFFF',
			width: 53,
			height: 52
		},
		// This style will be used for clusters with more than 10 markers
		10: {
			image: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/images/m2.png',
			textColor: '#FFFFFF',
			width: 56,
			height: 55
		},
		20: {
			image: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/images/m3.png',
			textColor: '#FFFFFF',
			width: 66,
			height: 65
		}
	};
	
	// Initialize Fluster
	// This will set event handlers on the map and calculate clusters the first time.	
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				});
			
		    
		    }  
		 



</script>
