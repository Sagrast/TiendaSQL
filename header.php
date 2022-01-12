<!DOCTYPE html>
<?php
    //Autor: Oscar González Martínez
    //Version: 1.0
    //Cabecero index
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>

            .text {
                text-align: left;
                font-size: 10px;
            }
            .links {
                text-align: right;
                margin-right: 20px;
            }
            .body {
                font-size: 15px;

            }
            a {
                text-decoration: none;
            }
            .bgcolor {
                padding: 2.7rem;
                background-color: #000500;
                color: lime;
                font-family: 'VT323', monospace;
            }
            .bgcolor img {
                width: 100px;
                height: auto;
            }
            .logo {
                font-size: 1.5rem;
                text-shadow: 0px 0px 2px GreenYellow;
                line-height: 1.7rem;
            }
            .logo:after {
                content: "";
                display: inline-block;
                width: 0.7rem;
                height: 1.1rem;
                background-color: lime;
                box-shadow: 0px 0px 1px GreenYellow;
                animation-name: dot;
                animation-duration: 0.9s;
                animation-iteration-count: infinite;
            }
            @keyframes dot {
                from {
                    background-color: lime;
                    box-shadow: 0px 0px 2px GreenYellow;
                }
                to {
                    background-color: #000500;
                    box-shadow: 0px 0px 2px #000500;
                }
            }

        </style>
    </head>
    <body style="font-size: <?php
    if (isset($_COOKIE['fuente'])) {
        echo $_COOKIE['fuente'];
    }
    ?>px;">    
        <div class="bgcolor">
            <img src="./img/cpc.png"/>
            <span class="logo">CPC Store </span>
            <div class="text">

            </div>


        </div>
    </body>
</html>
