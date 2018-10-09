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
		<?php echo $this->partial( DIR .'/_templates/partials/footer_none.php'); ?>
	<!--=================================
	 footer -->

	</div>
	<!-- wrapper End -->
		
	<div id="back-to-top"><a class="top arrow" href="#top"><i class="fa fa-angle-up"></i> <span>TOP</span></a></div>

	<!--=================================
	 javascripts -->
	<?php echo $this->partial( DIR .'/_templates/partials/scripts.php'); ?>

	
    </body>
</html>