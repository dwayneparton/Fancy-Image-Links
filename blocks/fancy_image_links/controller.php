<?php     
	Loader::block('library_file');
	defined('C5_EXECUTE') or die("Access Denied.");	
	class FancyImageLinksBlockController extends BlockController {

		protected $btInterfaceWidth = 500;
		protected $btInterfaceHeight = 500;
		protected $btTable = 'btFancyImageLinks';
		protected $btCacheBlockOutput = true;
		protected $btCacheBlockOutputOnPost = true;

		public function getBlockTypeDescription() {
			return t("Adds images and on-states from the library to pages and allows for that image to have no link at all, link to a file, a page, or to an external link that can be displayed in a fancybox iframe. Great for displaying youtube videos.");
		}
		
		public function getBlockTypeName() {
			return t("Fancy Image Links");
		}		
		
		public function getJavaScriptStrings() {
			return array(
				'image-required' => t('You must select an image.'),
				'file-required' => t('You must select a file.')
			);
		}
		
		//Load Fancybox if Enabled
		public function on_page_view() {
			if ($this->enable_lightbox) {
				$html = Loader::helper('html');				
				$bv = new BlockView();
				$bv->setBlockObject($this->getBlockObject());
				$this->addHeaderItem($html->css($bv->getBlockURL() . '/fancybox/jquery.fancybox-1.3.4.css'));
				$this->addHeaderItem($html->javascript($bv->getBlockURL() . '/fancybox/jquery.fancybox-1.3.4.pack.js'));
			}
		}
		
		//Edit Form Variables
		public function edit() {
			$this->set('fID', (empty($this->fID) ? null : File::getByID($this->fID)));
			$this->set('fOnstateID', (empty($this->fOnstateID) ? null : File::getByID($this->fOnstateID)));
			$this->set('fileLinkID', (empty($this->fileLinkID) ? null : File::getByID($this->fileLinkID)));
			$this->set('imageLinkID', (empty($this->imageLinkID) ? null : File::getByID($this->imageLinkID)));
		}
			
		//Save the Form
		public function save($data) {		
			$args['fOnstateID'] = ($data['fOnstateID'] != '') ? $data['fOnstateID'] : 0;
			$args['fID'] = ($data['fID'] != '') ? $data['fID'] : 0;
			$args['max_width'] = (intval($data['max_width']) > 0) ? intval($data['max_width']) : 0;
			$args['max_height'] = (intval($data['max_height']) > 0) ? intval($data['max_height']) : 0;
			$args['link_method'] = isset($data['link_method']) ? $data['link_method'] : 'no_link';
			$args['external_link'] = isset($data['external_link']) ? $data['external_link'] : '';
			$args['external_link_title'] = isset($data['external_link_title']) ? $data['external_link_title'] : '';
			$args['external_link_target'] = isset($data['external_link_target']) ? 1 : 0;
			$args['alt_text'] = isset($data['alt_text']) ? $data['alt_text'] : '';
			$args['img_class'] = isset($data['img_class']) ? $data['img_class'] : '';
			$args['enable_lightbox'] = isset($data['enable_lightbox']) ? 1 : 0;
			$args['lightbox_effect'] = isset($data['lightbox_effect']) ? $data['lightbox_effect'] : 'fade';
			$args['lightbox_title_position'] = isset($data['lightbox_title_position']) ? $data['lightbox_title_position'] : 'inside';
			$args['lightbox_width'] = (intval($data['lightbox_width']) > 0) ? intval($data['lightbox_width']) : 560;
			$args['lightbox_width_type'] = isset($data['lightbox_width_type']) ? $data['lightbox_width_type'] : 'px';
			$args['lightbox_height'] = (intval($data['lightbox_height']) > 0) ? intval($data['lightbox_height']) : 340;
			$args['lightbox_height_type'] = isset($data['lightbox_height_type']) ? $data['lightbox_height_type'] : 'px';
			$args['lightbox_description'] = isset($data['lightbox_description']) ? $data['lightbox_description'] : '';
			$args['lightbox_overlay_color']  = $data['lightbox_overlay_color'];
			$args['fileLinkID'] = ($data['fileLinkID'] != '') ? $data['fileLinkID'] : 0;
			$args['pageLinkID'] = ($data['pageLinkID'] != '') ? $data['pageLinkID'] : 0;
			$args['imageLinkID'] = ($data['imageLinkID'] != '') ? $data['imageLinkID'] : 0;
			$args['filesetLinkID'] = ($data['filesetLinkID'] != '') ? $data['filesetLinkID'] : 0;
			parent::save($args);
		}
		
		//Generate the Image HTML
		public function generateImage(){
			
			$f = File::getByID($this->fID);
			$fullPath = $f->getPath();
			$relPath = $f->getRelativePath();			
			$size = @getimagesize($fullPath);
			
			if ($this->max_width > 0 || $this->max_height > 0) {
				$mw = $this->max_width > 0 ? $this->max_width : $size[0];
				$mh = $this->max_height > 0 ? $this->max_height : $size[1];
				$ih = Loader::helper('image');
				$thumb = $ih->getThumbnail($f, $mw, $mh);
				$sizeStr = ' width="' . $thumb->width . '" height="' . $thumb->height . '"';
				$relPath = $thumb->src;
			} else {
				$sizeStr = $size[3];
			}
			
			$img = "<img border=\"0\" class=\"ccm-image-block {$this->img_class}\" alt=\"{$this->alt_text}\" src=\"{$relPath}\" {$sizeStr} ";

			if($this->fOnstateID != 0) {
				$fos =  File::getByID($this->fOnstateID);
				
				if ($this->max_width > 0 || $this->max_height > 0) {
					$thumbHover = $ih->getThumbnail($fos, $mw, $mh);				
					$relPathHover = $thumbHover->src;
				} else {
					$relPathHover = $fos->getRelativePath();
				}

				$img .= " onmouseover=\"this.src = '{$relPathHover}'\" ";
				$img .= " onmouseout=\"this.src = '{$relPath}'\" ";
			}
			
			$img .= "/>";
			
			return $img;
		}
		
		//Generate the HTML for a given method
		public function generateHTML($link_method){
			$img = $this->generateImage();
			$rel = 'fancybox'.$this->bID;
			
			//Nothing
			if($link_method == 'no_link' || $link_method == ''){
				$html = $img ;
			}
			
			
			//URL
			if($link_method == 'url_link'){
				if($this->external_link_target == 1){$target = ' target="_blank"';}
				else{$target = '';}
				$html = '<a rel="'.$rel.'" href="'.$this->external_link.'" '.$target.' title="'.$this->lightbox_description.'">'. $img .'</a>';
			}
			
			//Page
			if($link_method == 'page_link'){
				$nh = Loader::helper('navigation');
				$p = Page::getByID($this->pageLinkID);
				$page_name = $p->getCollectionName();
				$page_link = $nh->getLinkToCollection($p); 
				$html = '<a href="'.$page_link.'" title="'.$page_name.'">'. $img .'</a>';
			}
			
			//Download
			if($link_method == 'file_link'){
				$f = File::getByID($this->fileLinkID);
				$file_link = $f->getDownloadURL();
				$file_name = $f->getTitle();
				$html = '<a href="'.$file_link.'" title="'.$file_name.'">'. $img .'</a>';
			}
			
			//Image
			if($link_method == 'image_link'){
				$f = File::getByID($this->imageLinkID);
				$image_link = $f->getRelativePath();
				$image_description = $f->getDescription();
				$html = '<a rel="'.$rel.'" href="'.$image_link.'" title="'.$image_description.'">'. $img .'</a>';
			}
			
			//Fileset
			if($link_method == 'fileset_link'){
				$html = '<a rel="'.$rel.'">'. $img .'</a>';
			}
			
			
			return $html;
			
		}
		
		public function getFileSet() {
			 
   			Loader::model('file_set');
    		Loader::model('file_list');
    		$fs = FileSet::getById($this->filesetLinkID);    		
    		$files = array();
    		// if the file set exists (may have been deleted) 
    		if ($fs->fsID) {
				$this->fileSetName = $fs->getFileSetName(); 
				$fl = new FileList();
				$fl->filterBySet($fs); 
				$fl->sortByFileSetDisplayOrder();
				$files = $fl->get((int)$this->numberFiles, 0);
			}  		 
			return $files;
		}
			
		function view(){
			$this->set('enable_lightbox', $this->enable_lightbox);
			$this->set('lightbox_effect', $this->lightbox_effect);
			$this->set('lightbox_title_position', $this->lightbox_title_position);
			$this->set('rel','fancybox'.$this->bID);
			$this->set('html', $this->generateHTML($this->link_method));
			if($this->link_method == 'fileset_link'){
				$this->set('files', $this->getFileSet());
			}
		}

	}

?>