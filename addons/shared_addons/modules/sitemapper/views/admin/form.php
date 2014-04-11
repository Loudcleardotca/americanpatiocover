<style>
    #navigation-module label, #navigation-page label {
        width: 27% !important;
        float: left;
        text-align: left;
        margin: 0 3% 0 0;
        padding: 8px;
    }
</style>

<section class="title">
    <h4><?php echo lang('sitemap_exclude_create_title');?></h4>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open(uri_string(), 'class="crud"'); ?>
        <div class="tabs">

            <ul class="tab-menu">
                <li><a href="#sitemap-content-tab"><span><?php echo 'Content'; ?></span></a></li>
            </ul>

            <div class="form_inputs" id="sitemap-content-tab">
                <fieldset>
                    <ul>
                        <li class="<?php echo alternator('', 'even'); ?>">
                            <label for="link_type"><?php echo lang('sitemap_type_label');?></label>

                            <div class="input">
                                <?php echo form_radio('link_type', 'module', $link->link_type == 'module') ?><?php echo lang('sitemap_module_label');?>
                                <?php echo form_radio('link_type', 'page', $link->link_type == 'page') ?><?php echo lang('sitemap_page_label');?>
                            </div>
                        </li>

                        <li class="<?php echo alternator('', 'even'); ?>">
                            <div class="no_data"
                                 style="<?php echo (!empty($link->link_type)) ? ('display:none') : (null); ?>">
                                <?php echo lang('sitemap_link_type_desc');?>
                            </div>

                            <div id="navigation-module"
                                 style="<?php echo @$link->link_type == 'module' ? '' : 'display:none'; ?>">
                                <label for="module_i"><?php echo lang('sitemap_module_label');?></label>

                                <div class="input"><?php if (!empty($module_selects)) {
                                        echo form_dropdown('module_id', array(lang('nav_link_module_select_default')) + $module_selects, $link->module_id);
                                    } else {
                                        echo '<div class="no_data">' . lang('out_of_modules') . '</div>';
                                    } ?></div>
                            </div>
                            <div id="navigation-page"
                                 style="<?php echo @$link->link_type == 'page' ? '' : 'display:none'; ?>">
                                <label for="page_id"><?php echo lang('sitemap_page_label');?></label>

                                <div class="input"><?php if ($tree_select != NULL) { ?>
                                        <select name="page_id">
                                            <option value=""><?php echo lang('nav_link_page_select_default');?></option>
                                            <?php echo $tree_select; ?>
                                        </select>
                                    <?php
                                    }
                                    else {
                                        echo '<div class="no_data">' . lang('out_of_pages') . '</div>';
                                    } ?></div>
                            </div>
                        </li>
                    </ul>
                    <div class="buttons align-right padding-top">
                        <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel'))); ?>
                    </div>
                    <?php echo form_close(); ?>
                </fieldset>
            </div>
        </div>
    </div>
</section>