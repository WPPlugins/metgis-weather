<span class="metgisform">
<?php
	
	$this->print_input($instance,"key","MetGISAPIKey");
	echo "<small>".$this->lang('CALLLIMIT')."</small>";
	$this->print_input($instance,"title","Titel");
	$this->print_input($instance,"width","PluginWidth",'number" min="200" max="1024" placeholder="100%"');
	echo "<small>".$this->lang('WIDTHNOTICE')."</small>";
	$this->print_input($instance,"location","KoordinatenLATLON");
	$this->print_input($instance,"alt","Hoehe");
	$this->print_input($instance,"days","TageVorschau",'number" min="1" max="7"');
	echo "<p>";
	$this->print_checkbox($instance,"showDescription","Beschreibunganzeigen"); echo "<br />";
	$this->print_checkbox($instance,"showTemp","Temperaturanzeigen"); echo "<br />";
	$this->print_checkbox($instance,"showPrecipitation","Niederschlaganzeigen"); echo "<br />";
	$this->print_checkbox($instance,"showRainfall","Rainfallanzeigen"); echo "<br />";
	$this->print_checkbox($instance,"showSnow","Schneefallanzeigen"); echo "<br />";
	$this->print_checkbox($instance,"showHumidity","Feuchtigkeitanzeigen"); echo "<br />";
	$this->print_checkbox($instance,"showWind","Windanzeigen"); echo "<br />";
	$this->print_checkbox($instance,"showSunDuration","Sonnendaueranzeigen"); echo "<br />";
	$this->print_checkbox($instance,"showSunRiseSet","Aufunterganganzeigen"); echo "<br />";
	
	$this->print_checkbox($instance,"releatedBG","WetterabhaengigerHintergrund"); echo "<br />";
	$this->print_checkbox($instance,"showDays","Wochentageanzeigen"); echo "<br />";
	$this->print_checkbox($instance,"showKMS","kmstattms"); echo "<br />";
	$this->print_checkbox($instance,"show451","FstattC"); echo "<br />";	
	$this->print_checkbox($instance,"showINCH","inchstattm"); echo "<br />";
	$this->print_checkbox($instance,"makeLandscape","makeLandscape"); echo "<br />";
	


	
	
	echo "</p>";
	$this->print_radio($instance,"descriptionFormat","Titelanzeigen",array("0"=>"Abkuerzungen","1"=>"Volltext","2"=>"Icons"));
	echo "<p>";
	?>
	
	<p>
		<label for="<?php echo $this->get_field_name( 'dateFormat' ); ?>"><?php echo $this->lang('DATEFORMAT'); ?>:</label>
		<select class="widefat" name="<?php echo $this->get_field_name( 'dateFormat' ); ?>" id="<?php echo $this->get_field_id( 'dateFormat' ); ?>" >
			<option value="d.m.Y" <?php echo (($instance['dateFormat']=="d.m.Y") ? "selected" : "") ?>>dd.mm.yyyy (<?php echo date("d.m.Y")?>)</option>
			<option value="Y-m-d" <?php echo (($instance['dateFormat']=="Y-m-d") ? "selected" : "") ?>>yyyy-mm-dd (<?php echo date("Y-m-d")?>)</option>
			<option value="d-m-Y" <?php echo (($instance['dateFormat']=="d-m-Y") ? "selected" : "") ?>>dd-mm-yyyy (<?php echo date("d-m-Y")?>)</option>
			<option value="d/m/Y" <?php echo (($instance['dateFormat']=="d/m/Y") ? "selected" : "") ?>>dd/mm/yyyy (<?php echo date("d/m/Y")?>)</option>
			<option value="m-d-Y" <?php echo (($instance['dateFormat']=="m-d-Y") ? "selected" : "") ?>>mm-dd-yyyy (<?php echo date("m-d-Y")?>)</option>
			<option value="m/d/Y" <?php echo (($instance['dateFormat']=="m/d/Y") ? "selected" : "") ?>>mm/dd/yyyy (<?php echo date("m/d/Y")?>)</option>
		</select>
		
	</p>
	<p>
		<label for="<?php echo $this->get_field_name( 'timeFormat' ); ?>"><?php echo $this->lang('TIMEFORMAT'); ?>:</label>
		<select class="widefat" name="<?php echo $this->get_field_name( 'timeFormat' ); ?>" id="<?php echo $this->get_field_id( 'timeFormat' ); ?>" >
			<option value="H:i" <?php echo (($instance['timeFormat']=="H:i") ? "selected" : "") ?>>24h (<?php echo date("H:i")?>)</option>
			<option value="h:i a" <?php echo (($instance['timeFormat']=="h:i a") ? "selected" : "") ?>>am/pm (<?php echo date("h:i a")?>)</option>
		</select>
	</p>
	
	<?php	
	$this->print_input($instance,"color","Schriftfarbe",'text" data-wp="color');
	$this->print_input($instance,"shadowColor","Schriftschatten",'text" data-wp="color');
 	
  $image = (isset($instance['bgImage'])) ? $instance['bgImage'] : '';
  ?>
  <p>
      <label for="<?php echo $this->get_field_name( 'bgImage' ); ?>"><?php echo $this->lang('BACKGROUNDIMAGE'); ?></label><br />
      <input name="<?php echo $this->get_field_name( 'bgImage' ); ?>" id="<?php echo $this->get_field_id( 'bgImage' ); ?>" type="text" size="20"  value="<?php echo esc_url( $image ); ?>" />
      <input class="upload_image_button" type="button" value="<?php echo $this->lang('SELECT_IMAGE') ?>" />
			<input onClick="jQuery(this).prev().prev().val('')" type="button" value="<?php echo $this->lang('USE_STANDARD') ?>" />
  </p>
	<?php
	$this->print_checkbox($instance,"releatedBG","WetterabhaengigerHintergrund"); echo "<br /><p><small>".$this->lang('BGNOTICE')."</small></p>";
	
		
	?>
	<p>
		<label for="<?php echo $this->get_field_name( 'linkTarget' ); ?>"><?php echo $this->lang('PAGE_DETAIL') ?></label><br />
		
	<?php 	$this->print_checkbox($instance,"showLink","DetailLink"); echo "<br />"; ?>
		<small><?php echo $this->lang('PAGEDETAIL_INFO') ?></small><br />
		
<?php

wp_dropdown_pages(array(
    'id' => $this->get_field_id('linkTarget'),
    'name' => $this->get_field_name('linkTarget'),
    'selected' => $instance['linkTarget'],
));
?>
</p>
<?php
	$this->print_radio($instance,"icons","Iconsetverwenden",array("1"=>"Iconset1","2"=>"Iconset2","3"=>"Iconset3"));
	
	
?>
</span>

<script type='text/javascript'>
	jQuery(document).ready(function($) {
		$('input[data-wp=color]').wpColorPicker();
		$(".metgisform").parent().parent().prop("enctype","multipart/form-data");
		
		
    $(document).on("click", ".upload_image_button", function() {

        jQuery.data(document.body, 'prevElement', jQuery(this).prev());

        window.send_to_editor = function(html) {
					console.log(html);
            var imgurl = jQuery(html).attr('href');
						var preview = jQuery(html).find("img").attr('src');
            var inputText = jQuery.data(document.body, 'prevElement');

            if(inputText != undefined && inputText != '')
            {
                inputText.val(imgurl);
            }

            tb_remove();
        };

        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
        return false;
    });
		
		
		
	});
	

</script>