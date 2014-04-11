<div class="one_half">
	<section class="title">
		<h4><?php echo lang('images:item_list'); ?></h4>
	</section>
	
	<section class="item">
		<div id="page-list">
		<ul class="sortable">
			<?php echo tree_builder($pages, '<li id="page_{{ id }}"><div><a href="#" rel="{{ id }}">{{ title }}</a></div>{{ children }}</li>'); ?>
		</ul>
		</div>
	</section>
</div>

<div class="one_half last">	
	<section class="title">
		<h4><?php echo lang('images:tree_explanation_title'); ?></h4>
	</section>
	
	<section class="item">
		<div id="page-details">
		<p>
			<?php echo lang('images:tree_explanation'); ?>
		</p>
		</div>
	</section>
</div>