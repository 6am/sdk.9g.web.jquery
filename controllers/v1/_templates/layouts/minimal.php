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

    <!--=================================
     header -->
    <?php echo $this->partial( DIR .'/_templates/partials/header.php'); ?>
    <!--=================================
     header -->

	<?php echo $this->partial($this->PartialType);?>

	<!--=================================
	 footer -->
		<?php echo $this->partial( DIR .'/_templates/partials/footer_minimal.php'); ?>
	<!--=================================
	 footer -->

	</div>
	<!-- wrapper End -->

	<!--=================================
	 javascripts -->
	<?php echo $this->partial( DIR .'/_templates/partials/scripts.php'); ?>

	
    </body>
</html>