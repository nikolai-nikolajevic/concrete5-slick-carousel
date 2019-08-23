<script type="text/template" id="item-template">
    <div class="item panel panel-default" data-order="<%=sort%>">
        <div class="panel-heading">
            <!-- IMAGE SELECTOR --->
            <div>
                <a href="javascript:chooseImage(<%=sort%>);" class="select-image" id="select-image-<%=sort%>">
                    <% if (thumb.length > 0) { %>
                        <img src="<%= thumb %>" />
                    <% } else { %>
                        <i class="fa fa-picture-o"></i>
                    <% } %>
                </a>
                <input type="hidden" name="<?php  echo $view->field('fID')?>[]" class="image-fID" value="<%=fID%>" />
            </div>
            <div>
                <a href="javascript:editItem(<%=sort%>);" class="btn btn-edit-item btn-default"><?php echo t('Edit')?></a>
                <a href="javascript:deleteItem(<%=sort%>)" class="btn btn-delete-item btn-danger"><?php echo t('Delete')?></a>

                <i class="fa fa-arrows drag-handle"></i>
            </div>
        </div>
        <div class="panel-body">
            
            <div class="form-group">
                <label class="control-label" for="title<%=sort%>"><?php echo t('Title:')?></label>
                <input class="form-control" type="text" name="title[]" id="title<%=sort%>" value="<%=title%>">
            </div>

            <div class="form-group" >
                <label class="control-label"><?php echo t('Description:'); ?></label>
                <textarea id="ccm-slide-editor-<%= _.uniqueId() %>" style="display: none" class="editor-content editor-content-<?php echo $bID; ?>" name="<?php echo $view->field('carcontent'); ?>[]"><%=carcontent%></textarea>
            </div>
                        
            <input class="item-sort" type="hidden" name="<?php  echo $view->field('sort')?>[]" value="<%=sort%>"/>
            
        </div>
    </div><!-- .item -->
</script>