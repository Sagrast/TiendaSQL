DROP DATABASE IF EXISTS tienda;

CREATE DATABASE IF NOT EXISTS tienda;

use tienda;

/* 
    TABLA USUARIOS
*/
DROP TABLE IF EXISTS usuarios;

CREATE TABLE IF NOT EXISTS usuarios (
    codigoUser SERIAL,
    rol varchar(15),
    userLogin VARCHAR(30) UNIQUE,
    contrasinal VARCHAR(120),
    nome  VARCHAR(150),
    enderezo VARCHAR(150),
    email VARCHAR(255),
    PRIMARY KEY(codigoUser)
);
INSERT INTO usuarios VALUES
(NULL,'Administrador','Oscar','$2y$10$tW4Bury2xAyamPquzcofw.sGyUKliQJmX6l0n8sCYWvFd9lBswbF2','Oscar Gonzalez Martinez','Bajo un Puente','oscar@uchazon.es'),
(NULL,'Usuario','Guybrush','$2y$10$JPC4KX8P6uzskoJOwlu88OOJ/pCZNJEnHPGvBd0NAagHowvDBN.pq','Guybrush Threepwood','Melee Island','guybrush@melee.com'),
(NULL,'Usuario','LeChuck','$2y$10$JhMtTbDDywGqk1xgQvvU.OOdEuaROdSp8kshjMBeygfyUE6hTFx6m','Pirata Fantasma LeChuck','Melee Island','chuckie@melee.com'),
(NULL,'Usuario','Larry','$2y$10$XtgkRuLPgwwthwi54Qic0uH9H4LrIs38MdcoQy4XHrzO5h2HxIici','Larry Laffer','Contenedor Número 5','larry@sierra.com'),
(NULL,'Usuario','Adso','$2y$10$5muJnolUqLXJFgwEKtFfKukyl4xgzs43bTAnKFtxbFB5xhIGQvFaa',"Adso de Melk","La abadia del Crimen",'adso@melk.com'),
(NULL,'Administrador','Marcos','$2y$10$4HJM8WDfcslHul1rFWZq6.OEtXi7N2dHFpDZ9GdMDe2jHX94NedIC','Marcos','Ucha','marcos@ucha.gal');


/* 
    TABLA PRODUCTOS
*/

DROP TABLE IF EXISTS productos;

CREATE TABLE IF NOT EXISTS productos (
    codigoProd SERIAL,
    nome VARCHAR(120),
    descricion VARCHAR(255),
    unidades INT,
    prezo DECIMAL(8,2),
    fotos VARCHAR(255),
    ive INT,
    PRIMARY KEY(codigoProd)
);

INSERT INTO productos VALUES 
(NULL,'La Abadia del Crimen','Videoaventura Isometrica',20,100,'./img/abadiacarga88.jpg',21),
(NULL,'Batman The Movie','Arcade multigenero',23,47,'./img/batman_the_movie_carga57.jpg',21),
(NULL,'Renegade','Peleas callejeras',10,25,'./img/renegade_carga10.png',10),
(NULL,'Robocop','Arcade multigenero',17,24,'./img/robocop_carga23.jpg',4);


/* 
    TABLA PEDIDOS
*/

DROP TABLE IF EXISTS pedidos;

CREATE TABLE IF NOT EXISTS pedidos (
    codigoPedidos SERIAL,    
    cantidad INT,
    precio_total DECIMAL(8,2),
    codigoUser BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY(codigoPedidos),
    CONSTRAINT FK_USER_PROD
        FOREIGN KEY (codigoUser)
        REFERENCES usuarios(codigoUser)        
        ON UPDATE CASCADE
        ON DELETE CASCADE        
);

INSERT INTO pedidos VALUES 
(NULL,1,100,2),
(NULL,2,47,4),
(NULL,3,94,2),
(NULL,4,50,1),
(NULL,5,75,5),
(NULL,6,100,3)
;

/* 
    TABLA Productos - Pedidos
*/

DROP TABLE IF EXISTS rel_pedidos;

CREATE TABLE IF NOT EXISTS rel_pedidos (
    codigoPedidos BIGINT UNSIGNED NOT NULL,
    codigoProd BIGINT UNSIGNED NOT NULL,
    fecha DATETIME,
    PRIMARY KEY(codigoPedidos,codigoProd,fecha),
    CONSTRAINT FK_PEDIDOS
    FOREIGN KEY(codigoPedidos)
        REFERENCES pedidos(codigoPedidos)
        ON UPDATE CASCADE
        ON DELETE CASCADE,        
    CONSTRAINT FK_PRODUCTOS
    	FOREIGN KEY(codigoProd)
        REFERENCES productos(codigoProd)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

INSERT INTO rel_pedidos VALUES 
(1,2,"2021-12-10 10:20:21"),
(2,3,"2021-11-12 11:34:22"),
(1,1,"2020-12-12 11:34:22"),
(2,2,"2019-1-12 1:34:22"),
(3,3,"2020-1-1 2:34:22"),
(4,4,"2021-1-1 11:34:22")
;
