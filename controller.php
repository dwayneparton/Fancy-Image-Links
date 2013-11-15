<?php        

defined('C5_EXECUTE') or die(_("Access Denied."));

class FancyImageLinksPackage extends Package {

	protected $pkgHandle = 'fancy_image_links';
	protected $appVersionRequired = '5.3.0';
	protected $pkgVersion = '1.3.7';
	
	public function getPackageName() {
		return t("Fancy Image Links");
	}
	
	public function getPackageDescription() {
		return t("Adds images and on-states from the library to pages and allows for that image to have no link at all, link to a file, a page, or to an external link that can be displayed in a fancybox iframe. Great for displaying youtube videos.");
	}
	
	public function install() {
		$pkg = parent::install();
		// install block		
		BlockType::installBlockTypeFromPackage('fancy_image_links', $pkg);
		
	}

}