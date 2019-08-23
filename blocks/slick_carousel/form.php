<?php 
defined('C5_EXECUTE') or die("Access Denied.");

$editorJavascript = Core::make('editor')->outputStandardEditorInitJSFunction();

// LOAD FOR REDACTOR & FILE SELECTOR
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission(); 

// Load form styles. Don't like to see it here :P
include_once 'partials/form_styles.html';

$tabs = [
    ['pane-items' . $getString, t('Elements'), true],
    ['pane-settings' . $getString, t('Settings')],
];
echo Core::make('helper/concrete/ui')->tabs($tabs);

?>

<div class="ccm-tab-content" id="ccm-tab-content-pane-items">
    
    <div class="items-container">
        
        <!-- DYNAMIC ITEMS WILL GET LOADED INTO HERE (partials/form_item.php) -->
        
    </div>  
    
    <span class="btn btn-success btn-add-item"><?php  echo t('Add Slide') ?></span> 

</div>

<div class="ccm-tab-content" id="ccm-tab-content-pane-settings">
    
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <?php echo $form->checkbox($view->field('hideDots'), 1, $hideDots);?>
                <?php echo $form->label('hideDots',t("Hide Dots")); ?>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <?php echo $form->checkbox($view->field('hideArrows'), 1, $hideArrows); ?>
                <?php echo $form->label('hideArrows',t("Hide Arrows")); ?>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <?php echo $form->checkbox($view->field('infinite'), 1, $infinite); ?>
                <?php echo $form->label('infinite',t("Infinite")); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">            
            <div class="form-group">
                <?php echo $form->label('itemsmobile',t("Items on Mobile")); ?>
                <?php echo $form->select('itemsmobile', array('1'=>'1','2'=>'2','3'=>'3','4'=>'4'), $itemsmobile?$itemsmobile:'1'); ?>
            </div>            
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?php echo $form->label('itemstablet',t("Items on Tablet")); ?>
                <?php echo $form->select('itemstablet', array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10'), $itemstablet?$itemstablet:'2'); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?php echo $form->label('itemsdesktop',t("Items on Desktop")); ?>
                <?php echo $form->select('itemsdesktop', array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10'), $itemsdesktop?$itemsdesktop:'4'); ?>
            </div>
        </div>        
    </div>
    <div class="row">
        
        <div class="col-xs-6">
            <div class="form-group">
                <?php  echo $form->label('thumbwidth',t('Thumb Width')); ?>
                <div class="input-group">
                    <?php  echo $form->text('thumbwidth',$thumbwidth?$thumbwidth:'300'); ?>
                    <div class="input-group-addon">px</div>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <?php  echo $form->label('thumbheight',t('Thumb Height')); ?>
                <div class="input-group">
                    <?php  echo $form->text('thumbheight',$thumbheight?$thumbheight:'200'); ?>
                    <div class="input-group-addon">px</div>
                </div>
            </div>
        </div>
        
    </div>
    
</div>

<!-- THE TEMPLATE WE'LL USE FOR EACH ITEM -->
<?php include_once 'partials/form_item.php' ?>
<!--  -->


<script type="text/javascript">
    // Editor vars
    var CCM_EDITOR_SECURITY_TOKEN = "<?php echo Core::make('helper/validation/token')->generate('editor'); ?>";
    var launchEditor = <?=$editorJavascript; ?>;

    //Edit Button
    var editItem = function(i){
        $(".item[data-order='"+i+"']").find(".panel-body").toggle();
    };
    //Delete Button
    var deleteItem = function(i) {
        var confirmDelete = confirm('<?php  echo t('Are you sure?') ?>');
        if(confirmDelete == true) {
            $(".item[data-order='"+i+"']").remove();
            indexItems();
        }
    };
    //Choose Image
    var chooseImage = function(i){
        var imgShell = $('#select-image-'+i);
        ConcreteFileManager.launchDialog(function (data) {
            ConcreteFileManager.getFileDetails(data.fID, function(r) {
                jQuery.fn.dialog.hideLoader();
                var file = r.files[0];
                imgShell.html(file.resultsThumbnailImg);
                imgShell.next('.image-fID').val(file.fID)
            });
        });
    };

    //Index our Items
    function indexItems(){
        $('.items-container .item').each(function(i) {
            $(this).find('.item-sort').val(i);
            $(this).attr("data-order",i);
            $(this).find(".btn-edit-item").attr("href", "javascript:editItem(" + i + ")");
            $(this).find(".btn-delete-item").attr("href", "javascript:deleteItem(" + i + ")");
        });
    };

    $(function(){

        //Define container and items
        var itemsContainer = $('.items-container');
        var itemTemplate = _.template($('#item-template').html());
    
        //BASIC FUNCTIONS
    
        //Make items sortable. If we re-sort them, re-index them.
        $(".items-container").sortable({
            handle: ".drag-handle",
            update: function(){
                indexItems();
            }
        });
    
        //LOAD UP OUR ITEMS
        
        //for each Item, apply the template.
        <?php  
        if($items) {
            foreach ($items as $item) { 
        ?>
        itemsContainer.append(itemTemplate({
            //define variables to pass to the template.
            
            //IMAGE SELECTOR
            fID: '<?php  echo $item['fID'] ?>',
            <?php  if($item['fID']) { ?>
            thumb: '<?php  echo File::getByID($item['fID'])->getThumbnailURL('file_manager_listing');?>',
            <?php  } else { ?>
            thumb: '',
            <?php  } ?>
            
            title: '<?php  echo addslashes($item['title']) ?>',
                        
            // Editor
            carcontent: '<?php  echo str_replace(array("\t", "\r", "\n"), "", addslashes($item['carcontent']))?>',
            
            sort: '<?php echo $item['sort'] ?>'
        }));
        <?php  
            }
        }
        ?>  

        //CREATE NEW ITEM
        $('.btn-add-item').click(function(){
            
            //Use the template to create a new item.
            var temp = $(".items-container .item").length;
            temp = (temp);
            itemsContainer.append(itemTemplate({
                //vars to pass to the template
                //IMAGE SELECTOR
                fID: '',
                thumb: '',
                
                title: '',
                
                // Editor
                carcontent: '',                
                
                sort: temp
            }));
            
            var thisModal = $(this).closest('.ui-dialog-content');
            var newItem = $('.items-container .item').last();
            thisModal.scrollTop(newItem.offset().top);
            
            // Init Editor
            launchEditor(newItem.find('.editor-content'));
            
            //Init Index
            indexItems();
        }); 

        //Init Index
        indexItems();
    });
    
    // Activate Editors
    $(document).ready(function() {
        $(function() {
            if ($('.editor-content-<?php echo $bID; ?>').length) {
                launchEditor($('.editor-content-<?php echo $bID; ?>'));
            }
        });
    });
</script>