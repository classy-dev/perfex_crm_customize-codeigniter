<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="_buttons">
							<a href="<?php echo admin_url('roles/role'); ?>" class="btn btn-info pull-left display-block mright5"><?php echo _l('new_role'); ?></a>
						</div>
						<div class="_buttons">
							<a href="<?php echo admin_url('roles/role_type'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_role_type'); ?></a>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<div class="clearfix"></div>
						<?php render_datatable(array(
							_l('roles_dt_name'),
							_l('options')
							),'roles'); ?>
						<hr class="hr-panel-heading" />	
						<div class="clearfix"></div>
						<?php render_datatable(array(
							_l('role_type_dt_name'),
							_l('options')
							),'role_type'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php init_tail(); ?>
	<script>
		initDataTable('.table-roles', window.location.href, [1], [1]);
		initDataTable('.table-role_type', '<?php echo admin_url('roles/get_role_type');?>', [1], [1]);
	</script>
</body>
</html>
