<!DOCTYPE html>
<html lang="en">
  <head>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Mars</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <meta name="theme-color" content="#F0DB4F"> -->
    <meta name="MobileOptimized" content="width">
    <meta name="HandheldFriendly" content="true">

    <!-- PWA config -->
    <link rel="manifest" href="manifest.json">
    <!-- iconos  -->
    <link rel="apple-touch-icon" href="images/icon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">

    <!-- <link rel="apple-touch-icon" sizes="152x152" href="images/icon.png">
    <link rel="apple-touch-icon" sizes="167x167" href="images/mars_167x167.png"> -->

    <!-- titulo icono -->
    <meta name="apple-mobile-web-app-title" content="Mars">
    <!-- standalone -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- bar color  -->
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#fdfdfd">

    <!-- splash  -->
    <link href="images/splashscreens/iphone5_splash.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="images/splashscreens/iphone6_splash.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="images/splashscreens/iphoneplus_splash.png" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="images/splashscreens/iphonex_splash.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="images/splashscreens/iphonexr_splash.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="images/splashscreens/iphonexsmax_splash.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="images/splashscreens/ipad_splash.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="images/splashscreens/ipadpro1_splash.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="images/splashscreens/ipadpro3_splash.png" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="images/splashscreens/ipadpro2_splash.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="/js/jquery.min.js" ></script>

    <link rel="stylesheet" type="text/css" media="screen" href="/css/style.css" />
    <script src="/js/home.js" ></script>

  </head>
  <body>
    <nav class="navbar">
        <div class="container">
        <img src="/images/logo.svg" class="logo-svg"  alt="" >
        <span id="name"><strong>MARS</strong></span>
        <form class="contenedor-usuario" id="logout-form" action="{{ route('logout') }}" method="POST" >
            <button type="submit" id="btn-logout">
                <a class="usuario" href="">
                    <i class="material-icons user-icon">account_circle</i>
                </a>
            </button>
        </form>
        </div>
    </nav>

    <div class="background-image"></div>
    <div class="container" id="home-container"></div>

    <script src="./js/script.js"></script>
  </body>
</html>