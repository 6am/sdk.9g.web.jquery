<?php

    $route->respond("GET","/default", function ($request, $response, $service) {
        $globals = add_javascript(
            array(
            )
        );

        $globals = add_css(
            array(
            )
        );

        $service->layout( DIR . "/_templates/layouts/default.php");
        $service->pageTitle = 'Default';
        $service->render(false);
        exit;
    });

?>