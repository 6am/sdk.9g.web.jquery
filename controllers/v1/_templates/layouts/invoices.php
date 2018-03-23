<div class="container">
    <div class="row">
        <div class="col-lg-12 bg-white">
            <div class="card card-transparent">
                <div class="card-block d-flex flex-wrap">
                    <div class="col-lg-12 sm-no-padding m-t-30">
                        <div class="col-lg-3 pull-left">
                            <h5 class="text-left f-bold">Invoices</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class=" no-padding container-fixed-lg bg-white m-t-30">
            <div class="container">

                <?php echo $this->partial( DIR .'/invoices/_templates/partials/invoices-datatables.php'); ?>

            </div>
        </div>
    </div>
</div>





