<?php
//each route is in your own folder
    $route->respond("GET","/", function ($request, $response, $service) {
        $globals = add_javascript(
            array(
            )
        );

        $globals = add_css(
            array(
            )
        );

        $service->layout( DIR . "/_templates/layouts/default.php");
        $service->PartialType = DIR . "/_templates/layouts/home.php";
        $service->pageTitle = 'Home';
        $service->render(false);
        exit;
    });
?>