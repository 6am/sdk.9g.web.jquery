<?php

    $route->respond("GET","/sales-statments", function ($request, $response, $service) {
        $globals = add_javascript(
            array(
                '/html/vendors/select2/js/select2.full.min.js',


                '/html/vendors/jquery-datatable/media/js/jquery.dataTables.min.js',
                '/html/vendors/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js',
                '/html/vendors/jquery-datatable/media/js/dataTables.bootstrap.js',
                '/html/vendors/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js',
                '/html/vendors/datatables-responsive/js/datatables.responsive.js',
                '/html/vendors/datatables-responsive/js/lodash.min.js',



                '/html/assets/js/sales-statment.js'
            )
        );

        $globals = add_css(
            array(
                '/html/vendors/select2/css/select2.min.css',
                '/html/assets/css/sales-statment.css',

                '/html/vendors/jquery-datatable/media/css/dataTables.bootstrap.min.css',
                '/html/vendors/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css',
                '/html/vendors/datatables-responsive/css/datatables.responsive.css'
            )
        );

        $service->layout( DIR . "/_templates/layouts/default.php");
        $service->PartialType = DIR . "/_templates/layouts/sales-statments.php";
        $service->pageTitle = 'Sales Statment';
        $service->render(false);
        exit;
    });
?>