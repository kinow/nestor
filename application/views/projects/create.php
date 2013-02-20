<h1>Create project</h1>

<?php echo validation_errors(); ?>

<?php echo form_open(); ?>
	<?php echo form_label('Name', 'name'); ?>
	<?php echo form_input('name'); ?>
	<?php echo form_submit('', 'Add'); ?>
<?php echo form_close(); ?>