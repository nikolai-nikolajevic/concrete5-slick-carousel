<?php       

namespace Concrete\Package\SlickCarousel;

use Concrete\Core\Asset\AssetList;
use Package;
use BlockType;
use Route;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package
{

	protected $pkgHandle = 'slick_carousel';
	protected $appVersionRequired = '8.0.0';
	protected $pkgVersion = '1.0';
	
    protected $pkgAutoloaderRegistries = [
        'src/SlickCarousel' => '\Concrete\Package\SlickCarousel\Src\SlickCarousel',
    ];
	
	public function getPackageDescription()
	{
		return t("slick - the last carousel you'll ever need.");
	}

	public function getPackageName()
	{
		return t("Slick Carousel Block");
	}
	
	public function install()
	{
		$pkg = parent::install();
        BlockType::installBlockTypeFromPackage('slick_carousel', $pkg);
        
    }

    public function on_start()
    {
        Route::register('/slick/getData/{bID}', '\Concrete\Package\SlickCarousel\Src\SlickCarousel\Helper::getData');

        $al = AssetList::getInstance();
        $al->register(
            'javascript', 'slickJS', '../packages/slick_carousel/blocks/slick_carousel/slick/slick.min.js',
            array('version' => '1.8.1', 'minify' => false, 'combine' => false)
        );
        $al->register(
            'css', 'slickCSS', '../packages/slick_carousel/blocks/slick_carousel/slick/slick.css',
            array('version' => '1.8.1', 'minify' => false, 'combine' => false)
        );
        $al->register(
            'css', 'slickTheme', '../packages/slick_carousel/blocks/slick_carousel/slick/slick-theme.css',
            array('version' => '1.8.1', 'minify' => false, 'combine' => false)
        );
    }
    
    public function uninstall()
    {
        parent::uninstall();
        $db = \Database::connection();
        $db->query('DROP TABLE IF EXISTS btSlickCarousel');
        $db->query('DROP TABLE IF EXISTS btSlickCarouselItem');
    }
}