<section class="title">
	<h4><?php echo lang('thumbnails:'.$this->method); ?></h4>
</section>

<section class="item">

	<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>
   
		<div class="form_inputs" >
            <ul>
                <li class="<?php echo alternator('', 'even'); ?>">
                    <label for="name"><?php echo lang('thumbnails:name'); ?> <span>*</span></label>
                    <div class="input"><?php echo form_input('name', set_value('name', $thumbnails->name), 'class="width-15"'); ?></div>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                    <label for="slug"><?php echo lang('thumbnails:slug'); ?> <span>*</span></label>
                    <div class="input"><?php echo form_input('slug', set_value('slug', $thumbnails->slug), 'class="width-15"'); ?></div>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                    <label for="layout"><?php echo lang('thumbnails:layout'); ?> <span>*</span></label>
                    <div class="input">
                        <?php echo form_dropdown('layout', $page_layouts, $thumbnails->layout); ?>
                    </div>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                    <label for="folder"><?php echo lang('thumbnails:folder'); ?> <span>*</span></label>
                    <div class="input">
                    	<?php if(!empty($file_folders)) : ?>
                        	<?php echo form_dropdown('folder', $file_folders, $thumbnails->folder); ?>
                        <?php else : ?>
                        	<a href="<?php echo base_url(); ?>index.php/admin/files"><?php echo lang('thumbnails:no_folders'); ?></a>
                		<?php endif; ?>
                    </div>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                    <label for="width"><?php echo lang('thumbnails:width'); ?> <span>*</span></label>
                    <div class="input"><?php echo form_input('width', set_value('width', $thumbnails->width), 'class="width-15"'); ?></div>
                </li>
                <li class="<?php echo alternator('', 'even'); ?>">
                    <label for="height"><?php echo lang('thumbnails:height'); ?> <span>*</span></label>
                    <div class="input"><?php echo form_input('height', set_value('height', $thumbnails->height), 'class="width-15"'); ?></div>
                </li>
            </ul>
		</div>
		
		<div class="buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
		</div>
       
	<?php echo form_close(); ?>

</section>