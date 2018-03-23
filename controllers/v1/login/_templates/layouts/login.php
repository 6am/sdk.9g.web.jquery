<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <?php echo $this->partial(DIR . '/_templates/partials/header.php'); ?>
    </head>
    <body class="fixed-header menu-pin menu-behind">
        <div class="login-wrapper ">
            <!-- START Login Background Pic Wrapper-->
            <div class="bg-pic">
                <!-- START Background Pic-->
                <img src="#" data-src="#"
                     data-src-retina="#" alt="" class="lazy">
                <!-- END Background Pic-->
            </div>
            <!-- END Login Background Pic Wrapper-->
            <!-- START Login Right Container-->
            <div class="login-container bg-white">
                <div class="p-l-50 m-l-20 p-r-50 m-r-20 p-t-50 m-t-30 sm-p-l-15 sm-p-r-15 sm-p-t-40">
                    <p class="p-t-35"></p>
                    <div class="pull-top sm-pull-top">
                        <div class="m-b-30 p-r-80 sm-m-t-20 sm-p-r-15 sm-p-b-20 clearfix">
                            <div class="col-sm-3 col-md-2 no-padding">
                                <img alt="" class="m-t-5" data-src="#"
                                     data-src-retina="#"
                                     src="#" width="350">
                            </div>

                        </div>
                    </div>
                    <!-- START Login Form -->
                    <form id="form-login" class="p-t-15" role="form" action="/">
                        <!-- START Form Control-->
                        <div class="form-group form-group-default">
                            <label>Email</label>
                            <div class="controls">
                                <input type="text" name="login" placeholder="Email" class="form-control" required>
                            </div>
                        </div>
                        <!-- END Form Control-->
                        <!-- START Form Control-->
                        <div class="form-group form-group-default">
                            <label>Senha</label>
                            <div class="controls">
                                <input type="password" class="form-control" name="password" placeholder="Senha" required>
                            </div>
                        </div>

                        <!-- END Form Control-->
                        <button class="btn btn-six btn-block btn-cons m-t-10 bold text-uppercase text-black" type="submit">Logar</button>
                    </form>
                    <!--END Login Form-->


                </div>
            </div>
            <!-- END Login Right Container-->
        </div>

        <!-- BEGIN VENDOR JS -->
        <?php echo $this->partial(DIR . '/_templates/partials/footer.php'); ?>

        <style>
            .login-wrapper .bg-pic > img {
                opacity: 1 !important;
            }
        </style>
        <script>
            $(function () {
                $('#form-login').validate();
                $('#form-login').submit(function () {
                    if ($('#form-login').valid()) {
                        $data = $('#form-login').serializeArray();
                        $.ajax({
                            url: '/login',
                            type: 'post',
                            data: $data,
                            dataType: 'json'
                        }).done(function (data) {
                            if (data.login == true) {
                                window.location = data.reload;
                            }
                        });
                    }
                    return false;
                });
            });
        </script>
    </body>
</html>