<!DOCTYPE html>
<html>
	<!--================================= 
	head -->
    <?php echo $this->partial( DIR .'/_templates/partials/head.php'); ?>
	<!--================================= 
	head -->
	
	<body>
	<div class="wrapper">
	<!-- wrapper start -->

	<!--================================= preloader -->
    <?php echo $this->partial( DIR .'/_templates/partials/preloader.php'); ?>
	<!--=================================
	 preloader -->

	<?php echo $this->partial($this->PartialType);?>

	</div>
	<!-- wrapper End -->
		
	<!--=================================
	 javascripts -->
	<?php echo $this->partial( DIR .'/_templates/partials/scripts.php'); ?>

	
    </body>
</html>