<div class='page-header'>
	<h1>Create project</h1>
</div>

<?php echo validation_errors('<div class="alert alert-block alert-error fade in" data-dismiss="alert"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>'); ?>

<?php echo form_open('', array('class' => 'form-horizontal')); ?>
	<div class="control-group">
		<?php echo form_label('Name', 'name', array('class' => 'control-label')); ?>
		<div class="controls">
			<?php echo form_input('name', '', 'id="name" class="span10"'); ?>
		</div>
	</div>
	<div class="control-group">
		<?php echo form_label('Description', 'description', array('class' => 'control-label')); ?>
		<div class="controls">
			<?php echo form_textarea('description', '', 'id="description" rows="3" class="span10"'); ?>
		</div>
	</div>
	<div class="control-group">
		<div class='controls'>
			<?php echo form_submit('', 'Add', 'class="btn"'); ?>
		</div>
	</div>
<?php echo form_close(); ?>