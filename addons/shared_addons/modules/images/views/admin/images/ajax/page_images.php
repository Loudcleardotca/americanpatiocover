<script src="<?php base_url()?><?php echo ADDONPATH ?>modules/images/js/jquery.form.js"></script>
<script src="<?php base_url()?><?php echo ADDONPATH ?>modules/images/js/image.js"></script>
<fieldset>
<ul>
	<?php if($thumbnails) : ?>
    <?php foreach($thumbnails as $thumbnail) : ?>
    <li class="<?php echo alternator('', 'even'); ?>" id="<?php echo $thumbnail->id ?>" style="clear: both;">
        <label for="file"><?php echo $thumbnail->name ?> (<?php echo $thumbnail->width ?> x <?php echo $thumbnail->height ?>)</label><br /><br />
        <div style="clear: both; height: <?php echo $thumbnail->height ?>;">
            <div style="float: left; width: 270px;">
                <div class="thumbnail" style="border: 1px solid #D3D3D3; border-radius: 5px 5px 5px 5px; padding: 5px; margin-right: 10px; width: <?php echo $thumbnail->width ?>px; max-width: 250px;">
                    <img style="max-width: 250px;" src="<?php echo isset($images[$thumbnail->id]->thumbnail) ? BASE_URL.UPLOAD_PATH.'files/'.$images[$thumbnail->id]->thumbnail : 'http://placehold.it/'.$thumbnail->width.'x'.$thumbnail->height ?>" alt="<?php echo lang('images:image'); ?>" />
                </div><br />
                <a id="delete-image-button" data-thumbnail="<?php echo isset($images[$thumbnail->id]->thumbnail) ? $images[$thumbnail->id]->thumbnail : ''?>" data-image="<?php echo isset($images[$thumbnail->id]->image) ? $images[$thumbnail->id]->image : ''?>" data-thumbnail_id="<?php echo $thumbnail->id ?>" data-image_file_id ="<?php echo $images[$thumbnail->id]->image_file_id ?>" data-thumbnail_file_id="<?php echo $images[$thumbnail->id]->thumbnail_file_id ?>" data-page="<?php echo $this->uri->segment(4) ?>" class="btn red" href="#"><?php echo lang('images:delete_image'); ?></a><br /><br />
            </div>
            <div style="float: left;">
                <?php echo form_open_multipart(base_url().'index.php/admin/'.$this->module.'/ajax_upload_image', 'class="ajax-form-upload" id="'.$thumbnail->id.'"'); ?>
                    <input type="file" name="file"><br />
                    <input type="hidden" name="thumbnail_id" value="<?php echo $thumbnail->id ?>" />
                    <input type="hidden" name="page" value="<?php echo $this->uri->segment(4) ?>" />
                    <input type="submit" value="<?php echo lang('images:upload_image') ?>">
                <?php echo form_close() ?>            
            </div>
        </div>
		<hr />
    </li>
    <?php endforeach; ?>
    <?php else : ?>
    	<?php echo lang('images:no_items'); ?>
    <?php endif; ?>
</ul>
</fieldset>