<?php 
	$all_forms = leovw_getallforms();
	if($all_forms){	
?>
<form action="" name="contact_forms">
	Select the form: 
	<select name="contact_form">
		<?php foreach($all_forms as $each_form){ ?>
				<option value="<?php echo $each_form->contact_form_id; ?>" <?php if(@$_GET['contact_form'] == $each_form->contact_form_id){echo 'selected="selected"';}?>><?php echo $each_form->contact_form_name; ?></option>
		<?php } ?>
	</select>
	<input type="hidden" name="page" value="leovw" />
	<input type="submit" value="Filter" name="filter_form" />
</form>
<?php } //$all_forms ?>