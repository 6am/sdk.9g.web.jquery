<?php

    //home
    $route->respond("GET","/[:locale]/", function ($request, $response, $service) {
        $globals = add_javascript(
            array(
            )
        );
        switch($request->paramsNamed()["locale"]) {
            case "us":
                $locale="en_US";
                break;
            case "br":
                $locale="pt_BR";
                break;
        };
        $service->layout( DIR . "/_templates/layouts/minimal.php");
        $service->PartialType = DIR . "/_templates/layouts/home.php";
        $service->pageTitle = 'Humans Solutions ';
        $service->render(false);
        exit;
    });

    $route->respond("GET","/", function ($request, $response, $service) {
       //check location and REDIR
        $locale ="br";
        header("location:/".$locale."/");
        exit;
    });




    //login
    $route->respond("GET","/[:locale]/login", function ($request, $response, $service) {
        $service->layout( DIR . "/_templates/layouts/minimal.php");
        $service->PartialType = DIR . "/_templates/basics/login.php";
        $service->pageTitle = 'Home';
        $service->render(false);
        exit;
    });
    //signup
    $route->respond("GET","/[:locale]/iniciar", function ($request, $response, $service) {
        $service->layout( DIR . "/_templates/layouts/minimal.php");
        $service->PartialType = DIR . "/_templates/basics/signup.php";
        $service->pageTitle = 'Home';
        $service->render(false);
        exit;
    });
    $route->respond("GET","/[:locale]/esqueci-senha", function ($request, $response, $service) {
        $service->layout( DIR . "/_templates/layouts/minimal.php");
        $service->PartialType = DIR . "/_templates/basics/forgot.php";
        $service->pageTitle = 'Home';
        $service->render(false);
        exit;
    });

    //checkout
    $route->respond("GET","/[:locale]/checkout", function ($request, $response, $service) {
        $globals = add_javascript(
            array(
            )
        );

        $globals = add_css(
            array(
            )
        );

        $service->layout( DIR . "/_templates/layouts/minimal.php");
        $service->PartialType = DIR . "/_templates/checkout/checkout.php";
        $service->pageTitle = 'Home';
        $service->render(false);
        exit;
    });
?>