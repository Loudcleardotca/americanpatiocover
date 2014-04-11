<section class="title">
    <h4><?php echo lang('sitemap.sitemap_label'); ?></h4>
</section>

<section class="item">
  <div class="content">
      <div class="no_data">
          <?php echo lang('sitemapp.usage_info'); ?>
      </div>

      <?php echo form_open('admin/' . $this->module . '/delete'); ?>

      <?php if (!empty($excludes)): ?>
          <table border="0" class="table-list">
              <thead>
              <tr>
                  <th width="30"><?php echo form_checkbox(array('name'  => 'action_to_all',
                                                                'class' => 'check-all'));?></th>
                  <th><?php echo lang('sitemap_module_name') . '/' . lang('sitemap_page_label');?></th>
                  <th width="140"><?php echo lang('sitemap_link_type');?></th>
                  <th><?php echo lang('sitemapp.created_on_label'); ?></th>
                  <th width="100" style="text-align: center;"><?php echo lang('sitemap_actions_label'); ?></th>
              </tr>
              </thead>
              <tbody><?php
              foreach ($excludes as $exclude): ?>
                  <tr>
                  <td><?php
                      echo form_checkbox('action_to[]', $exclude->id); ?>
                  </td>
                  <td><?php
                      switch ($exclude->link_type) {
                          case 'module':
                              $module_names = unserialize($exclude->name);
                              $module_name = (isset($module_names[CURRENT_LANGUAGE])) ? ($module_names[CURRENT_LANGUAGE]) : ($module_names['en']);
                              echo anchor($exclude->slug, $module_name, array('class' => 'modal-large'));
                              break;
                          case 'page':
                              echo anchor($exclude->uri, $exclude->title, array('class' => 'modal-large'));
                              break;
                      } ?></td>
                  <td><?php echo lang('sitemap_' . $exclude->link_type . '_label');?></td>
                  <td><?php echo format_date($exclude->created_on); ?></td>
                  <td style="text-align: right;"><?php
                      echo anchor('admin/' . $this->module . '/delete/' . $exclude->id, lang('global:delete'), array('class' => 'confirm btn red delete')); ?>
                  </td>
                  </tr><?php
              endforeach; ?>
              </tbody>
          </table>
          <div class="table_action_buttons">
              <?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
          </div>
      <?php else: ?>
          <div class="blank-slate">
              <div class="no_data">
                  <?php echo lang('sitemapp.no_excludes'); ?>
              </div>
          </div>
      <?php endif;?>

      <?php echo form_close(); ?>
  </div>
</section>