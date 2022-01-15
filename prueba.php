<?php
    include "./class/DAO.php";

    //var_dump(DAO::userBDD());

    $nombre = 'Oscar';

    var_dump(DAO::esAdminBDD('oscar'));

?>