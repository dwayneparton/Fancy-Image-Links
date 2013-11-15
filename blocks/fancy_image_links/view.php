<?php     
defined('C5_EXECUTE') or die("Access Denied.");
$c = Page::getCurrentPage();
?>

<?php  echo $html; ?>

<?php  if (!$c->isEditMode() && $enable_lightbox == 1){?>
	<?php  //Url
	if($link_method == 'url_link'){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		$('a[rel="<?php  echo $rel; ?>"]').fancybox(
		{
			'transitionIn' : '<?php  echo $lightbox_effect; ?>',
			'transitionOut' : '<?php  echo $lightbox_effect; ?>',
			'titlePosition' : '<?php  echo $lightbox_title_position; ?>',
			'width' : <?php  if($lightbox_width_type == "%"){echo "'" . $lightbox_width . $lightbox_width_type . "'";}elseif($lightbox_width_type == "auto"){echo "'". $lightbox_width_type . "'";}else{echo $lightbox_width;} ?>,
			'height' : <?php  if($lightbox_height_type == "%"){echo "'" . $lightbox_height . $lightbox_height_type . "'";}elseif($lightbox_height_type == "auto"){echo "'". $lightbox_height_type . "'";}else{echo $lightbox_height;} ?>,
			'overlayColor': '<?php  echo $lightbox_overlay_color; ?>',
			'type' : 'iframe'
		});
	});
	</script>
	<?php  } ?>
	
	<?php  //Image
	if($link_method == 'image_link'){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		$('a[rel="<?php  echo $rel; ?>"]').fancybox(
		{
			'transitionIn'  : '<?php  echo $lightbox_effect; ?>',
			'transitionOut' : '<?php  echo $lightbox_effect; ?>',
			'titlePosition' : '<?php  echo $lightbox_title_position; ?>',
			'width' : <?php  if($lightbox_width_type == "%"){echo "'" . $lightbox_width . $lightbox_width_type . "'";}elseif($lightbox_width_type == "auto"){echo "'". $lightbox_width_type . "'";}else{echo $lightbox_width;} ?>,
			'height' : <?php  if($lightbox_height_type == "%"){echo "'" . $lightbox_height . $lightbox_height_type . "'";}elseif($lightbox_height_type == "auto"){echo "'". $lightbox_height_type . "'";}else{echo $lightbox_height;} ?>,
			'overlayColor': '<?php echo $lightbox_overlay_color; ?>'
		});
	});
	</script>
	<?php  } ?>
	
	<?php  //Filesets
	if($link_method == 'fileset_link'){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		$('a[rel="<?php  echo $rel; ?>"]').click(function(){
			$.fancybox(
			[
				<?php  
					foreach($files as $f) {
						$image = File::getByID($f->fID);
						$image_link = $image->getRelativePath();
						$image_description = $image->getDescription();
						if($i>0){echo ',';}
						echo "
							{
							'href': '$image_link',
							'title' : '$image_description'
							}
							";
						$i++;
					}
				?>
			],
			{
				'transitionIn' : '<?php  echo $lightbox_effect; ?>',
				'transitionOut' : '<?php  echo $lightbox_effect; ?>',
				'titlePosition' : '<?php  echo $lightbox_title_position; ?>',
				'width' : <?php  if($lightbox_width_type == "%"){echo "'" . $lightbox_width . $lightbox_width_type . "'";}elseif($lightbox_width_type == "auto"){echo "'". $lightbox_width_type . "'";}else{echo $lightbox_width;} ?>,
				'height' : <?php  if($lightbox_height_type == "%"){echo "'" . $lightbox_height . $lightbox_height_type . "'";}elseif($lightbox_height_type == "auto"){echo "'". $lightbox_height_type . "'";}else{echo $lightbox_height;} ?>,
				'overlayColor': '<?php  echo $lightbox_overlay_color; ?>'
			});
		});
	});
	</script>
	<?php  } ?>
	
<?php  }?>