<?php
/*
    Author: Marion Carambula
    Secciï¿½n de salas
*/
include ("./common/common-include.php");
//Verificar que el usuario tiene  permisos
$sectionId = "4";
if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" && $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0"){ header("Location: ./index.php"); exit();}
if (!isset($_SESSION["data"]["evento"])) {header("Location: ./index.php"); exit();}

$event      = $_SESSION["data"]["evento"];
$rooms      = $backend->getRoomList($event, $input["room"]["list"]["no-show"]);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("salas"); ?>
    <div class="content">
        <div class="title"><?= $label["Salas"]?> 
         <?php if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] != "cliente" || $_SESSION["app-user"]["permission"][$sectionId]["create"] == "1"){?>
        <a href="./manage_room.php" class="add"><?= $label["Añadir nueva"]?></a>
        <?php } ?>
        </div>
        <?= $globalMessage ?>
        <?php if ($rooms) { ?>
        <?= @$ui->buildTable($rooms)?>
        <?php }else{ ?>
            -- <?= $label["No hay salas disponibles"];?> --
        <?php }?>
    </div>
     <?= my_footer() ?>
  </body>
</html>                                                                                                                                                                        