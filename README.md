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

###Creating an virtual host
#####Linux
[https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts](#)

#####Windows
[https://stackoverflow.com/questions/2658173/setup-apache-virtualhost-windows](#)

#####MAC
[https://coolestguidesontheplanet.com/set-virtual-hosts-apache-mac-osx-10-9-mavericks-osx-10-8-mountain-lion/](#)

Then start the server

```
http://example.local
```

### How to componetize

Fisrtly - you need to cut the HTML code from every individual section from the layout, see below an example to the [http://example.local/home](#) route
```html
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->partial( DIR .'/_templates/partials/header.php'); ?>
    </head>
    <body>
        <div>
            <?php echo $this->partial( DIR .'/_templates/partials/top-bar.php'); ?>
        </div>
        <div>
            <section>
                <?php echo $this->partial($this->PartialType); ?>
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
<div>
    <div>
        <div>
            <?php include(DIR . "/home/_templates/layouts/confirmation-top.php"); ?>
            <div>
                 <?php include(DIR . "/home/_templates/layouts/detail-profile.php"); ?>
                 <?php include(DIR . "/home/_templates/layouts/balance.php"); ?>
                 <?php include(DIR . "/home/_templates/layouts/user-article.php"); ?>
            </div>
            <div>
                  <?php include(DIR . "/home/_templates/layouts/post-composer.php"); ?>
            </div>
        </div>
    </div>
</div>
```

For each individual section in our home page we call an partial file, let look inside the detail-profile.php file
```html
<div>
    <div>
        <div>
            <div>
                <img alt="Avatar" width="55" height="55"
                     data-src-retina="#"
                     data-src="#"
                     src="#">
            </div>
            <br>
            <p>
                <img src="/html/assets/midias/images/icons/badge.png"
                     alt="" width="20" data-toggle="tooltip"
                     data-placement="bottom" title="Lorem">
                <i class="fa fa-diamond text-success fs-14"
                   data-toggle="tooltip" data-placement="bottom"
                   title="Lorem"></i>
            </p>
        </div>
        <div>
            <h3>John Doe</h3>
            <p>Lorem ipsum dolor sit amet!</p>
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