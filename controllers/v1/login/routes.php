<?php

    $route->respond("POST","/login", function ($request, $response, $service) use ($NGCloud) {
        $send = $request->param('format', 'json');
        $data['email'] =  ($_POST['login']);
        $data['username'] = ($_POST['login']);
        $data['password'] = ($_POST['password']);
        $data['apikey'] = APIKEY;
        $data['apitoken'] = APITOKEN;

        $result = json_decode($NGCloud->post('api.9g.com.br/v1/auth', $data));

        if(strlen(trim($result->accesstoken)) > 5) {
            $_SESSION['userinfo'] = $result;
            $response->$send(array('login' => true, 'reload' => '/'));
        }
    });

    $route->respond("GET","/login", function ($request, $response, $service) {

        $globals = add_javascript(
            array(
                '/html/vendors/jquery-validation/js/jquery.validate.min.js'
            )
        );

        $globals = add_css(
            array(
            )
        );

        $service->layout( DIR . "/login/_templates/layouts/login.php");
        $service->pageTitle = 'Login';
        $service->render(false);
        exit;
    });

?>