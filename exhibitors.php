<?php
/*
    Author: Marion Carambula
    Sección de usuarios
*/
include ("./common/common-include.php");

//Verificar que el usuario tiene  permisos
$sectionId = "6";
if ($_SESSION["app-user"]["user"][1]["type"] == "cliente" && $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0"){ header("Location: ./index.php"); exit();}


$exhibitors = $backend->getExhibitorList($input["exhibitor"]["list"]["no-show"]);
$pagination = $ui->buildPagination(@$_GET["fireUI"]["currentPage"],$backend->app["count"]/$backend->app["perPage"]);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("expositores"); ?>
    <div class="content">
        <div class="title"><?= $label["Expositores"]?> 
         <?php if ($_SESSION["app-user"]["user"][1]["type"] == "administrador" || $_SESSION["app-user"]["permission"][$sectionId]["create"] == "1"){?>
             <a href="./manage_exhibitor.php" class="add"><?= $label["Añadir nuevo"]?></a>
        <?php } ?> 
        </div>
        <?= $globalMessage ?>
        <?php if ($exhibitors){ ?>
        <?=       @$ui->buildTable($exhibitors, 1,1) ?>
        <?=        $pagination ?>
        <?php }else{ ?>
                -- <?= $label["No hay expositores disponibles"];?> --
        <?php }?>
    </div>
  </body>
</html>                                                                                                                                                                        