<?php
if(!isset($info->error)) {
	$text = explode(". ",$data[0]->forecastText)[0];
	$currentdate = strtotime($info->dateFirstDay);
	if(strlen($text) > 50) $text= substr($text,48)."...";
	
	if(isset($instance['releatedBG'])) {
		$bgpth=str_replace("icons","bg",$imagepath);
		$instance['bgImage'] = $bgpth."/sun.jpg";
		switch ($data[0]->weatherIcon) {
			
			case "cloud_thunder_rain":
			case "cloud_bright_rain_hard":
			case"cloud_bright_rain_easy": $instance['bgImage'] = $bgpth."/rain.jpg"; break;
			
			case "cloud_thunder_snow":
			case "cloud_bright_snow_hard":
			case "cloud_bright_snow_easy": $instance['bgImage'] = $bgpth."/snow.jpg"; break;
			
			case "cloud_thunder_rain_snow": 
			case "cloud_bright_rain_snow_hard":
			case "cloud_bright_rain_snow_easy": $instance['bgImage'] = $bgpth."/snowrain.jpg"; break;
			
			case "cloudy_bright":
			case "cloud_bright_rain_drizzle":
			case "cloud_bright_snow_drizzle": $instance['bgImage'] = $bgpth."/clouded.jpg"; break;
			
			case "sunny":
			case "sun_bright_cloud": $instance['bgImage'] = $bgpth."/sun.jpg"; break;
			
			default: $instance['bgImage'] = $bgpth."/cloudy.jpg"; break;	
		
		}
	}
	if($instance['icons']=="") $instance['icons']="1";
	$imagepath.=$instance['icons']."/";
	
	$df=$instance['descriptionFormat'];
	$output ='<div id="metgiswather" '.( (isset($instance['makeLandscape']) && $instance['days'] > 1) ? 'class="landscape"' :'' ).' style="'.( (isset($instance['bgImage']) && $instance['bgImage']!="") ? 'background-image:url('.$instance['bgImage'].');' :'' ).( (isset($instance['width']) && $instance['width']!="") ? ' width:'.$instance['width'].'px;' :'' ).'">';
	
	$output.='<div class="wrapper" style="color:'.$instance['color'].'; text-shadow:1px 1px 0px '.$instance['shadowColor'].'">';
	$output.='<div class="title">'.$instance['title'].'</div>';
	
		$output.='<div class="current">';
			$output.='<div class="today name"><small>'.( (date($instance['dateFormat'],$currentdate) == date($instance['dateFormat'],strtotime($info->dateRequest))) ? $this->lang("TODAY") : $this->lang("TOMORROW") ).', '.( ($instance["showDayname"]) ? " ".$this->lang(strtoupper(date('l',$currentdate))) : '' ).' '.date($instance['dateFormat'],$currentdate)."</small></div>";
		
				$output.='<div class="state">';
					$output.='<div class="icon"><img src="'.$imagepath.$data[0]->weatherIcon.'.png" /></div>';
					if(isset($instance['showTemp'])) $output.='<div class="temp">'.(($df==2) ? '<img src="'.$imagepath.'temp_mid.png" />&nbsp;' : '' ).' '.$data[0]->maxTemp.'°'.$info->unitTemp.'<small>'.$data[0]->minTemp.'°'.$info->unitTemp.'</small></div>';
				$output.='</div>';
				
				$output.='<div class="detail">';
						if(isset($instance['showDescription']))  $output.="<div class='description'>".$data[0]->forecastShortText.'</div>'; 
						if(isset($instance['showPrecipitation']))  $output.=(($df==1) ? $this->lang("PRECIPITATION") : (($df==0) ? $this->lang("PRECIPITATION_SHORT") : '<div class="metgisicon"><img src="'.$imagepath.'precipitation.png" /></div>&nbsp;' )).' '.((isset($data[0]->precipitation)) ? $data[0]->precipitation : '0').(($info->unitLin == "m") ? " mm" : " in").'<br />';
						if(isset($instance['showRainfall']))  $output.=(($df=="1") ? $this->lang("RAIN") : (($df==0) ? $this->lang("RAIN_SHORT") : '<div class="metgisicon"><img src="'.$imagepath.'rain.png" /></div>&nbsp;' )).' '.$data[0]->rainfall.(($info->unitLin == "m") ? " mm" : " in").'<br />';
						if(isset($instance['showSnow']))  $output.=(($df=="1") ? $this->lang("SNOW") : (($df==0) ? $this->lang("SNOW_SHORT") : '<div class="metgisicon"><img src="'.$imagepath.'snow.png" /></div>&nbsp;' )).' '.((isset($data[0]->snowfall)) ? $data[0]->snowfall : '0')." ".(($info->unitLin == "m") ? " cm" : " in").'<br />';
						if(isset($instance['showHumidity']))  $output.=(($df=="1") ? $this->lang("HUMIDITY") : (($df==0) ? $this->lang("HUMIDITY_SHORT") : '<div class="metgisicon"><img src="'.$imagepath.'humidity.png" /></div>&nbsp;' )).' '.$data[0]->relativeHumidity.' %<br />';
						if(isset($instance['showWind']))  $output.=(($df=="1") ? $this->lang("WIND") : (($df==0) ? $this->lang("WIND_SHORT") : '<div class="metgisicon"><img src="'.$imagepath.'wind.png" /></div>&nbsp;' )).' '.$data[0]->windSpeed.' '.$info->unitWind.' '.(($df==2) ? '<img src="'.$imagepath.$data[0]->windDir.'.png" />' : $data[0]->windDir).'<br />';
						if(isset($instance['showSunDuration']))  $output.=(($df=="1") ? $this->lang("SUNDURATION") : (($df==0) ? $this->lang("SUNDURATION_SHORT") : '<div class="metgisicon"><img src="'.$imagepath.'sunduration.png" /></div>&nbsp;' )).' '.((isset($data[0]->sunshineDuration)) ? $data[0]->sunshineDuration : '0').' h<br />';
						
						$sun = array(date($instance['timeFormat'],strtotime(explode("+",$data[0]->sunrise)[0])),date($instance['timeFormat'],strtotime(explode("+",$data[0]->sunset)[0])));
						if($sun[1] == $sun[0]) { $sun[0]="-"; $sun[1]="-"; }
						if(isset($instance['showSunRiseSet']))  $output.=(($df=="1") ? $this->lang("SUNRISE") : (($df==0) ? $this->lang("SUNRISE_SHORT") : '<div class="metgisicon"><img src="'.$imagepath.'sunrise.png" /></div>&nbsp;' )).' '.$sun[0].' / '.$sun[1].'<br />';
																
			$output.='</div>';
			$output.='<div class="clearer"></div>';
		$output.='</div>';
		if($instance['days'] > 1) {
		$output.='<div class="forecast" style="border-top-color:'.$instance['color'].'">';
		unset($data[0]);
			foreach($data as $key=>$value) {
				$currentdate+=86400;
				$miniDateFormat = str_replace(array("Y-","-Y","/Y",".Y"),array("","","","."),$instance['dateFormat']);
			
				$output.='<div class="day" style="width:'.(100/count($data)).'%;">';
					$output.='<div class="name">'.( ($instance["showDays"]) ? $this->lang(strtoupper(date("D",$currentdate))) : strtoupper(date($miniDateFormat,$currentdate)) ).'</div>';
					$output.='<div class="icon"><img src="'.$imagepath.$value->weatherIcon.'.png" /></div>';
					$output.='<div class="temp">'.$value->minTemp.'°/'.$value->maxTemp.'°</div>';
				$output.='</div>';
			}
			$output.='<div class="clearer"></div>';
		$output.='</div>';
		}
		$output.='<div class="more" style="border-top-color:'.$instance['color'].'">';
				if(isset($instance['showLink']) && isset($info->widgetUrl) && $info->widgetUrl!="") {
					if(isset($instance['linkTarget'])) {
						$output.='<a href="?p='.$instance['linkTarget'].'&data='.base64_encode($info->widgetUrl).'" class="detaillink" style="color:'.$instance['color'].'">'.$this->lang("DETAILEDLINK").'</a>';
					} else {
						$output.='<a href="index.php?option=com_metgis&view=details&url='.base64_encode($info->widgetUrl).'" class="detaillink" style="color:'.$instance['color'].'">'.$this->lang("DETAILEDLINK").'</a>';
					}
				} 
				$output.='<a href="http://www.metgis.com" class="source" style="color:'.$instance['color'].'">'.$this->lang("METGISINFO").'</a>';
		$output.='</div>';
		$output.='<div class="clearer"></div>';
		
	$output.='</div>';
	$output.='<div class="clearer"></div>';

	$output.='</div>';
	echo $output;
	
}
else {
	echo $info->description;
}
	
	
	?>