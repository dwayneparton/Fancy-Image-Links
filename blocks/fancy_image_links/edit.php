<?php      
defined('C5_EXECUTE') or die("Access Denied.");
$al = Loader::helper('concrete/asset_library');
$fh = Loader::helper('form/color');

if($max_width<1){$max_width = '';}
if($max_height<1){$max_height = '';}
if($lightbox_width<1){$lightbox_width = '';}
if($lightbox_height<1){$lightbox_height = '';}
?>

<script type="text/javascript">
$(document).ready(function(){
   $('#tabset a').click(function(ev){
    var tab_to_show = $(this).attr('href');
    $('#tabset li').
      removeClass('ccm-nav-active').
      find('a').
      each(function(ix, elem){
        var tab_to_hide = $(elem).attr('href');
        $(tab_to_hide).hide();
      });
    $(tab_to_show).show();
    $(this).parent('li').addClass('ccm-nav-active');
    return false;
  }).first().click();
});
</script>

<ul id="tabset" class="ccm-dialog-tabs">
  <li><a href="#image"><?php  echo t('Image Settings')?></a></li>
  <li><a href="#display"><?php  echo t('Link Display Settings')?></a></li>
  <li><a href="#fancybox"><?php  echo t('Fancybox Settings')?></a></li>
</ul>

<div id="image" class="ccm-formBlockPane">
	<h2><?php  echo t('Image Settings'); ?></h2>
	<table width="100%">
		<tr>
			<td width="25%" valign="top"><?php  echo $form->label('image', 'Image:'); ?></td>
			<td width="75%" valign="top"><?php  echo $al->image('image', 'fID', t('Choose Image'), $fID);?></td>
		</tr>
		<tr>
			<td valign="top"><?php  echo $form->label('image-onstate', 'On-State(Optional):'); ?></td>
			<td valign="top"><?php  echo $al->image('image-onstate', 'fOnstateID', t('Choose Image On-State'), $fOnstateID);?></td>
		</tr>
		<tr>
			<td valign="top"><?php  echo $form->label('alt_text', 'Alt Text:'); ?></td>
			<td valign="top"><?php  echo  $form->text('alt_text', $alt_text, array('style' => 'width: 250px')); ?></td>
		</tr>
		<tr>
			<td valign="top"><?php  echo $form->label('img_class', 'CSS Class:'); ?></td>
			<td valign="top"><?php  echo  $form->text('img_class', $img_class, array('style' => 'width: 250px')); ?></td>
		</tr>
		<tr>
			<td valign="top"><?php  echo $form->label('max_width', 'Max Width:'); ?></td>
			<td valign="top"><?php  echo  $form->text('max_width', $max_width, array('style' => 'width: 60px')); ?>px</td>
		</tr>
		<tr>
			<td valign="top"><?php  echo $form->label('max_height', 'Max Height:'); ?></td>
			<td valign="top"><?php  echo  $form->text('max_height', $max_height, array('style' => 'width: 60px')); ?>px</td>
		</tr>
	</table>
</div>

<div id="display" class="ccm-formBlockPane">
	<h2><?php  echo t('Display Settings'); ?></h2>
	<table width="100%">
		<tr>
			<td width="25%" valign="top"><?php     echo $form->label('link_method', 'Link to:'); ?></td>
			<td width="75%" valign="top"><?php     echo $form->select('link_method', array('no_link' => 'Nothing', 'url_link' => 'External URL', 'page_link' => 'Page', 'file_link' => 'Download File', 'image_link' => 'Image', 'fileset_link' => 'Fileset'), $link_method); ?></td>
		</tr>
		<tr>
			<td valign="top"><?php   echo $form->label('enable_lightbox', 'Open in Fancybox:'); ?></td>
			<td valign="top"><?php   echo $form->checkbox('enable_lightbox', 1, $enable_lightbox, array('style' => 'margin-right: 0;')); ?></td>
		</tr>
	</table>
	<hr />
		
	<div id="no_link" class="option">
		<?php  echo t('Currently you are not linked to anything. This means that you are just going to be displaying an image.'); ?>
	</div>
	
	<div id="url_link" class="option">
	<table width="100%">
		<tr>
			<td width="25%" valign="top"><?php   echo $form->label('external_link', 'Link Url:'); ?></td>
			<td width="75%" valign="top"><?php   echo $form->text('external_link', $external_link, array('style' => 'width: 300px')); ?></td>
		</tr>
		<tr>
			<td valign="top"><?php   echo $form->label('lightbox_description', 'Title (Lightbox Caption):'); ?></td>
			<td valign="top"><?php   echo $form->textarea('lightbox_description', $lightbox_description, array('style' => 'width: 300px')); ?></td>
		</tr>
		<tr>
			<td width="30%" valign="top"><?php   echo $form->label('external_link_target', 'Open in New Window:'); ?></td>
			<td width="70%" valign="top"><?php   echo $form->checkbox('external_link_target', 1, $external_link_target, array('style' => 'margin-right: 0;')); ?></td>
				</td>
		</tr>
	</table>
	</div>
	
	<div id="file_link" class="option">
		<?php  echo t('Allows you to link to and download a file. It does not open in fancybox.'); ?>
		<?php  echo $al->file('fileLinkID', 'fileLinkID', t('Choose File'), $fileLinkID);?>
	</div>
	
	<div id="page_link" class="option">
		<?php  echo t('Allows you to link to a page. It does not open in fancybox.'); ?>
		<?php  $pageselect = Loader::helper('form/page_selector');
			echo $pageselect->selectPage('pageLinkID', $pageLinkID); ?>
	</div>
	
	<div id="image_link" class="option">
		<?php  echo $al->file('imageLinkID', 'imageLinkID', t('Choose Image'), $imageLinkID);?>
	</div>
	
	<div id="fileset_link" class="option">
		<?php 
			Loader::model('file_list');
			Loader::model('file_set');
			$filesets = FileSet::getMySets($user = false)
		?>
		<?php  echo t('Select Fileset:'); ?>
		<select name="filesetLinkID">
			<option value="0"><?php  echo t('Choose One'); ?></option>
			<?php   
				foreach ($filesets as $fset) {
					$fsn = $fset->getFileSetName();
					$fsID = $fset->getFileSetID();
					if($filesetLinkID == $fsID){$selected = 'selected';}else{$selected = '';}
					echo '"<option value="'.$fsID.'"'.$selected.'>'.$fsn.'</option>';
				}
			?>
		</select>
	</div>
		
</div>

<div id="fancybox" class="ccm-formBlockPane">
	<h2><?php  echo t('Fancybox Settings'); ?></h2>
	<div id="fancybox_settings">
		<table>
			<tr>
				<td valign="top"><?php   echo $form->label('lightbox_effect', 'Effect:'); ?></td>
				<td valign="top"><?php   echo $form->select('lightbox_effect', array('fade' => 'Fade', 'elastic' => 'Elastic', 'none' => 'None'), $lightbox_effect); ?></td>
			</tr>
			<tr>
				<td valign="top"><?php   echo $form->label('lightbox_height', 'Height:'); ?></td>
				<td valign="top"><?php   echo $form->text('lightbox_height', $lightbox_height, array('style' => 'width: 60px')); ?>
					<?php  echo $form->select('lightbox_height_type', array('px' => 'px', '%' => '%', 'auto' => 'auto'), $lightbox_height_type); ?></td>
			</tr>
			<tr>
				<td valign="top"><?php   echo $form->label('lightbox_width', 'Width:'); ?></td>
				<td valign="top"><?php   echo $form->text('lightbox_width', $lightbox_width, array('style' => 'width: 60px')); ?>
					<?php  echo $form->select('lightbox_width_type', array('px' => 'px', '%' => '%', 'auto' => 'auto'), $lightbox_width_type); ?></td>
			</tr>
			<tr>
				<td valign="top"><?php   echo $form->label('lightbox_title_position', 'Position:'); ?></td>
				<td valign="top"><?php   echo $form->select('lightbox_title_position', array('outside' => 'Outside', 'inside' => 'Inside', 'over' => 'Over'), $lightbox_title_position); ?></td>
			</tr>
			<tr>
				<td valign="top"><?php   echo $form->label('lightbox_overlay_color', 'Overlay Color:'); ?></td>
				<td valign="top"><?php   echo $fh->output('lightbox_overlay_color', '', empty($lightbox_overlay_color)?'#333333':$lightbox_overlay_color, $includeJavaScript = true); ?></td>
			</tr>
		</table>
	</div>
</div>