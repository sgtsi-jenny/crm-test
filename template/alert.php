<div class="alert alert-<?php echo $_SESSION[WEBAPP]['Alert']['Type'];?> alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>	
  <?php
  	echo $_SESSION[WEBAPP]['Alert']['Content'];
  ?>
</div>