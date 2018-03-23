<?php

    $route->respond("GET","/invoice", function ($request, $response, $service) {
        $globals = add_javascript(
            array(
            )
        );

        $globals = add_css(
            array(
            )
        );

        $service->layout( DIR . "/_templates/layouts/default.php");
        $service->PartialType = DIR . "/_templates/layouts/invoice.php";
        $service->pageTitle = 'Invoice';
        $service->render(false);
        exit;
    });
?>