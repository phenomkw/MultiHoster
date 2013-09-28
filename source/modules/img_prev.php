<?php
	$filename = $mmhclass->image->basename($mmhclass->input->get_vars['file']);
	echo '<center><a href="viewer.php?file='.$filename.'"><img src="img.php?image='.$filename.'" border="0"></a><br/><a href="viewer.php?file='.$filename.'">View Image in Viewer</a>';
?>