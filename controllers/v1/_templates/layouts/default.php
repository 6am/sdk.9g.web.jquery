<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->partial( DIR .'/_templates/partials/header.php'); ?>
    </head>
    <body class="fixed-header horizontal-menu horizontal-app-menu dashboard">
        <div class="header p-r-0 bg-primary">
            <?php
                echo $this->partial( DIR .'/_templates/partials/top-bar.php');
            ?>
        </div>
        <div class="page-container">
            <section id="content" class="animated fadeIn">
                <?php echo $this->partial($this->PartialType);?>
            </section>
        </div>
        <footer>
            <?php echo $this->partial( DIR .'/_templates/partials/footer.php'); ?>
        </footer>
    </body>
</html>