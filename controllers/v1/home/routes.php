<?php

    $route->respond("GET","/home", function ($request, $response, $service) {
        $globals = add_javascript(
            array(
                '/html/vendors/nvd3/lib/d3.v3.js',
                '/html/vendors/nvd3/nv.d3.min.js',
                '/html/assets/js/home.js'
            )
        );

        $globals = add_css(
            array(
                '/html/vendors/nvd3/nv.d3.min.css'
            )
        );

        $service->layout( DIR . "/_templates/layouts/default.php");
        $service->PartialType = DIR . "/_templates/layouts/home.php";
        $service->pageTitle = 'Dashboard';
        $service->render(false);
        exit;
    });
?>