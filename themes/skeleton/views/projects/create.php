<div>
	<h1>Create project</h1>
</div>

<?php echo validation_errors('<div class="alert alert-block alert-error fade in" data-dismiss="alert"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>'); ?>

<?php echo form_open('', array('class' => 'form-horizontal')); ?>
	<?php echo form_label('Name', 'name'); ?>
	<?php echo form_input('name', ''); ?>
	<?php echo form_label('Description', 'description'); ?>
	<?php echo form_textarea('description', ''); ?>
	<?php echo form_submit('', 'Add', 'class="btn"'); ?>
<?php echo form_close(); ?>