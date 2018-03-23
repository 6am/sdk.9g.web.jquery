### Installing

Put all the files in one folder, configure a virtual host to that folder.

```
_data
_design
_temp
cache
controllers
html
ngc
.gitignore
.htaccess
config.php
index.php
README.md
```

And start the server

```
example.local
```

### How to componetize

Explain what these tests test and why

Fisrtly - you need to cut the HTML code from every individual section from the layotu, see below an example to the [example.local/home](#) route
```html
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
```
The routes file call a default html structure with some partials, and then we will call the home component wich looks like this
```html
<div class="page-container ">
    <div class="page-content-wrapper ">
        <div class="content ">

            <?php include(DIR . "/home/_templates/layouts/confirmation-top.php"); ?>

            <div class="social-wrapper">
                <div class="social " data-pages="social">
                    <div class="container-fluid container-fixed-lg sm-p-l-0 sm-p-r-0">
                        <div class="feed">
                            <div class="day" data-social="day">
                                <div class="card no-border bg-transparent full-width" data-social="item">
                                    <div class="container p-t-30 p-b-30 ">
                                        <div class="row">
                                            <div class="offset-lg-1 col-lg-4">

                                                <?php include(DIR . "/home/_templates/layouts/detail-profile.php") ?>

                                                <?php include(DIR . "/home/_templates/layouts/balance.php") ?>

                                                <?php include(DIR . "/home/_templates/layouts/user-article.php") ?>

                                            </div>
                                            <div class="col-lg-6">

                                                <?php include(DIR . "/home/_templates/layouts/post-composer.php") ?>

                                                <?php

                                                    for($i =1; $i < 8; $i++){

                                                        include(DIR . "/home/_templates/layouts/post.php");

                                                    }

                                                ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

```

For each individual section in our home page we call an partial file, let look inside the detail-profile.php file
```html
<div class="container-xs-height">
    <div class="row-xs-height">
        <div class="social-user-profile col-xs-height text-center col-top">
            <div class="thumbnail-wrapper d48 circular bordered b-white">
                <img alt="Avatar" width="55" height="55"
                     data-src-retina="#"
                     data-src="#"
                     src="#">
            </div>
            <br>

            <p>
                <img src="/html/assets/midias/images/icons/badge.png"
                     alt="" width="20" data-toggle="tooltip"
                     data-placement="bottom" title="Membro Titan">
                <i class="fa fa-diamond text-success fs-14"
                   data-toggle="tooltip" data-placement="bottom"
                   title="Membro Diamond Club"></i>
            </p>


        </div>
        <div class="col-xs-height p-l-20">
            <h3 class="no-margin p-b-5">John Doe</h3>
            <p class="no-margin fs-16">Viva a vida Vitafine!
            </p>

        </div>
    </div>
</div>

```
In the folder this will looks like this

```
    .
    └── controllers                                 # Conains all the versions of the application
        └── v1                                      # version 1
            ├── _templates                          # necessary files
            │   ├── layouts                         # main layout files
            │   │   ├── default.php                 # default html structure
            │   │   └── home.php                    # home page layout and structure
            │   └── partials                        # essentials files for the app
            │       ├── breadcrumbs.php     
            │       ├── footer.php
            │       ├── header.php
            │       ├── sub-menu.php
            │       ├── top-bar.php
            │       └── top-menu.php
            └── home                                # the home folder with routes.php file
                ├── _templates                      # necessary files for the home page          
                ├── layouts                         main layout files for that route
                │       ├── balance.php
                │       ├── confirmation-top.php
                │       ├── detail-profile.php
                │       ├── post.php
                │       ├── post-composer.php
                │       └── user-article.php
                │       
                └── routes.php                      # routes instructions
```