<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <link rel="icon" type="image/png" href="g_logo.png">
        <!-- Bootstrap goes first -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Content placeholder for head content -->
        <?php echo $contentPlaceHolderHead; ?>
    </head>
    <body>
        <?php
        /* Get the name of the child page. Use this for ternary checks on links to avoid unnecessary calls to the server */
        $childPage = (basename($_SERVER["PHP_SELF"]));
        ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
            <div class="container">
                <!-- If the user is already on the page, replace link with URL fragment to avoid unnecessary calls to the server -->
                <a href="<?php echo ($childPage == "Index.php" ? "#" : "Index.php"); ?>" class="navbar-left" title="Home"><img src="g_logo.png" class="nav_logo"></a>
                <a class="navbar-brand" href="<?php echo ($childPage == "Index.php" ? "#" : "Index.php"); ?>" title="Home">PHP User Admin Shell</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item <?php if ($childPage == "Index.php") echo "active"; ?>">
                            <a class="nav-link" href="<?php echo ($childPage == "Index.php" ? "#" : "Index.php"); ?>" title="Home">Home</a>
                        </li>
                        <li class="nav-item <?php if ($childPage == "About.php") echo "active"; ?>">
                            <a class="nav-link" href="<?php echo ($childPage == "About.php" ? "#" : "About.php"); ?>" title="About">About</a>
                        </li>
                        <li class="nav-item <?php if ($childPage == "Login.php") echo "active"; ?>">
                            <a class="nav-link" href="<?php echo ($childPage == "Login.php" ? "#" : "Login.php"); ?>" title="Log In">Log In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://github.com/garciart/Master-Pages-In-PHP" target="_blank" title="GitHub Repository">GitHub <i class="fab fa-github"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <header class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <!-- Content placeholder for header element content -->
                    <?php echo $contentPlaceHolderHeader; ?>
                </div>
            </div>
        </header>
        <main class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- Content placeholder for main element content -->
                    <?php echo $contentPlaceHolderMain; ?>
                </div>
            </div>
        </main>
        <footer class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <hr>
                    <p class="mt-5 mb-3 text-muted">Copyright &copy; 2017<span id="currentYear"></span> Rob Garcia. All Rights Reserved.</p>
                    <!-- Content placeholder for main element content -->
                    <?php echo $contentPlaceHolderFooter; ?>
                </div>
            </div>
        </footer>
        <script>
            // Set copyright ending year to current year
            var date = new Date();
            var fullYear = date.getFullYear();
            if (fullYear !== 2017) {
                document.getElementById("currentYear").innerHTML = " - " + fullYear;
            }
        </script>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" crossorigin="anonymous"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
    </body>
</html>