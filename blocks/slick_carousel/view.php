<?php 
defined('C5_EXECUTE') or die(_("Access Denied.")); 
$ih = Core::make('helper/image');  
$nh = Core::make('helper/navigation'); 
$c = Page::getCurrentPage(); 
?>


<div class="slick-carousel" id="slick-carousel-<?php echo $bID?>">
    <?php  if($c->isEditMode()){
        $loc = Localization::getInstance();
        $loc->pushActiveContext(Localization::CONTEXT_UI); ?>
        <div class="ccm-edit-mode-disabled-item" style="<?php echo isset($width) ? "width: $width;" : ''; ?><?php echo isset($height) ? "height: $height;" : ''; ?>">
            <i style="font-size:40px; margin-bottom:20px; display:block;" class="fa fa-picture-o" aria-hidden="true"></i>
            <div style="padding: 40px 0px 40px 0px"><?php echo t('Image Slider disabled in edit mode.'); ?>
                <div style="margin-top: 15px; font-size:9px;">
                    <i class="fa fa-circle" aria-hidden="true"></i>
                    <?php if (count($items) > 0) { ?>
                        <?php foreach (array_slice($items, 1) as $item) { ?>
                            <i class="fa fa-circle-thin" aria-hidden="true"></i>
                    <?php
                        } 
                    } ?>
                </div>
            </div>
        </div>
        <?php
        $loc->popActiveContext();
    } else {?>
    <ul class="carousel-item-container" data-infinite="<?php echo $infinite?>" data-arrows="<?php echo $hideArrows?>" data-dots="<?php echo $hideDots?>" data-small-visible="<?php echo $itemsmobile?>" data-medium-visible="<?php echo $itemstablet?>" data-large-visible="<?php echo $itemsdesktop?>">
            <?php  
            foreach($items as $item){ 
                $imgObj = File::getByID($item['fID']); 
            ?>
            <li class="carousel-item">
                <div class="item-inner">
                    <?php  if (is_object($imgObj)){?>
                        <img src="<?php echo $ih->getThumbnail($imgObj,$thumbwidth,$thumbheight,true)->src?>">
                    <?php  } ?>
                    <h3><?php echo $item['title']?></h3>
                    <?php echo $item['carcontent']?>
                </div>
            </li>
        <?php  
        }//foreach ?>
    </ul>
    <?php  } ?>
</div>