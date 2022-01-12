<!DOCTYPE html>
<?php
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Menú</title>
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
                <?php
                if (isset($_COOKIE['visita'])) {
                    echo "Usuario: " . $_COOKIE['usuario'] . "<br/> Ultima Visita: " . $_COOKIE['visita'];
                } else {
                    echo $_COOKIE['usuario'] . " Benvid@ á páxina";
                    setcookie("visita", date("F j, Y, g:i a"), time() + 3600);
                }
                ?> 
                <?php
                $csv = DAO::leerUsuarios("usuarios.csv");
                if (DAO::esAdmin($_SESSION['userSesion'], $csv)) {
                    ?>
                </div>
                <div class="links">
                    <button><a href="index.php">Tenda</a></button>
                    <button><a href="cesta.php">Cesta</a></button>
                    <button><a href="usuarios.php">Usuarios</a></button>
                    <button><a href="productos.php">Productos</a></button>
                    <button><a href="perfil.php">Perfil</a></button>
                    <button><a href="logoff.php">Saír</a></button>
                </div>
                <?php
            } else {
                ?>
            </div>
            <div class="links">
                <button><a href="index.php">Tenda</a></button>
                <button><a href="cesta.php">Cesta</a></button>            
                <button><a href="perfil.php">Perfil</a></button>
                <button><a href="logoff.php">Saír</a></button>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>
