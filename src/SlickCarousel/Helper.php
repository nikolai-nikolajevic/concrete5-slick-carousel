<?php
namespace Concrete\Package\SlickCarousel\Src\SlickCarousel;
use Loader;
use Concrete\Core\Controller\Controller;

class Helper extends Controller
{
    public function getData($bID)
    {
        $db = Loader::db();
        $blockParameters = $db->GetAll('SELECT * from btSlickCarousel WHERE bID = ?', array($bID));

        echo json_encode($blockParameters[0]);
    }
}
