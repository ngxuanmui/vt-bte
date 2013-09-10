<?php

$item = $this->item;

?>

<div id="content">
	<?php 
	$field = JRequest::getString('field', 'content');
	
	if ($field == 'html_content')
		echo htmlspecialchars($item->html_content);
	else
		echo $item->content; 
	?>
</div>