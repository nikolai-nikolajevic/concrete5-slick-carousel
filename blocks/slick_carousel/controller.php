<?php 
namespace Concrete\Package\SlickCarousel\Block\SlickCarousel;
defined('C5_EXECUTE') or die("Access Denied.");

use \Concrete\Core\Block\BlockController;
use Loader;
use Page;
use View;

class Controller extends BlockController
{
    protected $btTable = 'btSlickCarousel';
    protected $btInterfaceWidth = "700";
    protected $btWrapperClass = 'ccm-ui';
    protected $btInterfaceHeight = "465";

    public function getBlockTypeDescription()
    {
        return t("Add a Carousel Content to your Site");
    }

    public function getBlockTypeName()
    {
        return t("Slick Carousel");
    }

    public function add()
    {
        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
    }

    public function edit()
    {
        $this->requireAsset('redactor'); 
        $this->requireAsset('core/file-manager'); 
        $this->requireAsset('core/sitemap');  
        
        View::getInstance()->addHeaderItem(Loader::helper('html')->css('/packages/slick_carousel/blocks/slick_carousel/partials/form_styles.css'));
        
        $db = Loader::db();
        $items = $db->GetAll('SELECT * from btSlickCarouselItem WHERE bID = ? ORDER BY sort', array($this->bID));
        $this->set('items', $items);
    }

    public function view()
    {
        // required Assets for view
        $this->requireAsset('javascript', 'jquery');
        $this->requireAsset('javascript', 'slickJS');
        $this->requireAsset('css', 'slickCSS');
        $this->requireAsset('css', 'slickTheme');

        $db = Loader::db();
        $items = $db->GetAll('SELECT * from btSlickCarouselItem WHERE bID = ? ORDER BY sort', array($this->bID));
        $this->set('items', $items);
    }

    public function duplicate($newBID) {
        parent::duplicate($newBID);
        $db = Loader::db();
        $v = array($this->bID);
        $q = 'select * from btSlickCarouselItem where bID = ?';
        $r = $db->query($q, $v);
        while ($row = $r->FetchRow()) {
            if(empty($args['fID'][$i])){$args['fID'][$i]=0;}
            $vals = array($newBID,$row['fID'][$i],$row['title'][$i],$row['carcontent'][$i],$row['sort'][$i]);  
            $db->execute('INSERT INTO btSlickCarouselItem (bID, fID, title, carcontent, sort) values(?,?,?,?,?)', $vals);
        }
    }

    public function delete()
    {
        $db = Loader::db();
        $db->delete('btSlickCarouselItem', array('bID' => $this->bID));
        parent::delete();
    }

    public function save($args)
    {
        $db = Loader::db();
        $db->execute('DELETE from btSlickCarouselItem WHERE bID = ?', array($this->bID));
        $count = count($args['sort']);
        $i = 0;

        // Handle Checkboxes
        $args['hideDots'] = isset($args['hideDots']) ? 1 : 0;
        $args['hideArrows'] = isset($args['hideArrows']) ? 1 : 0;
        $args['infinite'] = isset($args['infinite']) ? 1 : 0;

        parent::save($args);
        while ($i < $count) {
            if(empty($args['fID'][$i])){$args['fID'][$i]=0;}
            $vals = array($this->bID,$args['fID'][$i],$args['title'][$i],$args['carcontent'][$i],$args['sort'][$i]);     
            $db->execute('INSERT INTO btSlickCarouselItem (bID, fID, title, carcontent, sort) values(?,?,?,?,?)', $vals);
            $i++;
        }
    }
    
    public function validate($args)
    {
        $e = Loader::helper('validation/error');
        if(empty($args['thumbwidth'])){
            $e->add(t("Thumb Width must be set"));
        }
        if(!ctype_digit(trim($args['thumbwidth']))){
            $e->add(t("Thumb Width must be solely numeric"));
        }
        if(empty($args['thumbheight'])){
            $e->add(t("Thumb Width must be set"));
        }
        if(!ctype_digit(trim($args['thumbheight']))){
            $e->add(t("Thumb Width must be solely numeric"));
        }
        $count = count($args['sort']);
        for($i=0;$i<$count;$i++){
            if(strlen($args['title'][$i])>255){
                $e->add(t('The title in item %s is too long. Reduce it to 255 characters or less', $i+1));    
            }
        }
        
        return $e;
    }
    

}