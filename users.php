<?php
/*
    Author: Marion Carambula
    Sección de usuarios
*/
include ("./common/common-include.php");
//Verificar que el usuario tiene  permisos
$sectionId = "1";

if ($_SESSION["app-user"]["user"][1]["type"] == "cliente" && (!isset($_SESSION["app-user"]["permission"][$sectionId]["read"]) || $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0")){ header("Location: ./index.php"); exit();}

$out        = "";
$users      = $backend->getUserList($input["user"]["list"]["no-show"]);
$pagination = $ui->buildPagination(@$_GET["fireUI"]["currentPage"],$backend->app["count"]/$backend->app["perPage"]);

//print_r($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("usuarios"); ?>
    <div class="content">
        <div class="title"><?= $label["Usuarios"]?>
        <?php if ($_SESSION["app-user"]["user"][1]["type"] == "administrador" || @$_SESSION["app-user"]["permission"][$sectionId]["create"] == "1"){?>
         <a href="./manage_user.php" class="add"><?= $label["Añadir nuevo"]?></a>
        <?php } ?>
        </div>
        <?= $globalMessage ?>
        <?php if ($users){ ?>
        <?=       @$ui->buildTable($users, 1,1) ?>
        <?=       $pagination ?>
        
        <?php }else{ ?>
                -- <?= $label["No hay usuarios disponibles"];?> --
        <?php }?>
    </div>
  </body>
</html>                                                                                                                                                                        