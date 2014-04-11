<section class="title">
	<h4><?php echo lang('thumbnails:item_list'); ?></h4>
</section>

<section class="item">
	<?php echo form_open('admin/images/thumbnails/delete');?>
	
	<?php if (!empty($items)): ?>
	
		<table class="sortable-table">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th><?php echo lang('thumbnails:name'); ?></th>
                    <th><?php echo lang('thumbnails:slug'); ?></th>
                    <th><?php echo lang('thumbnails:width'); ?></th>
                    <th><?php echo lang('thumbnails:height'); ?></th>
                    <th><?php echo lang('thumbnails:usage'); ?></th>
					<th></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5">
						<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach( $items as $item ): ?>
				<tr id="<?php echo $item->id?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->name; ?></td>
                    <td><?php echo $item->slug; ?></td>
                    <td><?php echo $item->width; ?></td>
                    <td><?php echo $item->height; ?></td>
                    <td>{{ images:image page="{{ page:id }}" slug="<?php echo $item->slug; ?>" }}</td>
					<td class="actions">
						<?php echo
						anchor('admin/images/thumbnails/edit/'.$item->id, lang('general:edit'), 'class="button"').' '.
						anchor('admin/images/thumbnails/delete/'.$item->id, lang('general:delete'), array('class'=>'button')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
		
	<?php else: ?>
		<div class="no_data"><?php echo lang('thumbnails:no_items'); ?></div>
	<?php endif;?>
	
	<?php echo form_close(); ?>
</section>