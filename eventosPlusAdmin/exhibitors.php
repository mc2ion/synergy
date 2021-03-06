<?php
/*
    Author: Marion Carambula
    SecciÃ³n de expositores
*/
include ("./common/common-include.php");

//Verificar que el usuario tiene  permisos
$sectionId = "7";
if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" && $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0"){ header("Location: ./index.php"); exit();}
if (!isset($_SESSION["data"]["evento"])) {header("Location: ./index.php"); exit();}

$exhibitors = $backend->getExhibitorList($_SESSION["data"]["evento"], $input["exhibitor"]["list"]["no-show"]);
unset($_SESSION["exhibitor"]["image_path"]);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("expositores"); ?>
    <div class="content">
        <div class="title"><?= $label["Expositores"]?> 
         <?php if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] != "cliente"|| $_SESSION["app-user"]["permission"][$sectionId]["create"] == "1"){?>
             <a href="./manage_exhibitor.php" class="add"><?= $label["Añadir nuevo"]?></a>
        <?php } ?> 
        </div>
        <?= $globalMessage ?>
        <?php if ($exhibitors){ ?>
        <?=       @$ui->buildTable($exhibitors) ?>
        <?php }else{ ?>
                -- <?= $label["No hay expositores disponibles"];?> --
        <?php }?>
    </div>
     <?= my_footer() ?>
  </body>
</html>                                                                                                                                                                        