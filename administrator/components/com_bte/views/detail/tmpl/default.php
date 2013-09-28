<?php

$item = $this->item;

?>
<style>
<!--
label {
	font-weight: bold;
}
-->
</style>
<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo 'URL'; ?></legend>
			<ul class="adminformlist">
				<li><label>Url: </label>
				<?php echo $item->url; ?></li>
				
				<?php if ($this->item->id): ?>
				<li><label>Website</label>
					<span style="float: left; line-height: 23px;">
						<?php echo $this->item->website; ?>
					</span>
				</li>
				<li>
					<div style="float: left; width: 100%; font-weight: bold; line-height: 25px;">						
							<label>Content before extract</label>
					</div>
					<div style="float: left; width: 100%; text-align: justify; line-height: 20px;">
						<?php $field = JRequest::getString('field', 'content');
	
							if ($field == 'html_content')
								echo htmlspecialchars($item->html_content);
							else
								echo $item->content; 
						?>
					</div>
				</li>
				<?php endif; ?>
			</ul>
		</fieldset>
</div>
<?php 
/*
<div id="url">
	<?php echo $item->url; ?>
</div>
<div id="site">
	<?php echo $item->website; ?>
</div>
<div id="content" style="text-align: justify;">
	<?php 
	$field = JRequest::getString('field', 'content');
	
	if ($field == 'html_content')
		echo htmlspecialchars($item->html_content);
	else
		echo $item->content; 
	?>
</div>*/?>