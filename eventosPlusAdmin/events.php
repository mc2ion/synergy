<?php
/*
    Author: Marion Carambula
    SecciÃ³n de eventos
*/
include ("./common/common-include.php");

//Verificar que el usuario tiene  permisos
$sectionId = "3";
if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" && $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0"){ header("Location: ./index.php"); exit();}

$events     = $backend->getEventList($_SESSION["data"]["cliente"], $input["event"]["list"]["no-show"]);

unset($_SESSION["event"]["map_path"]);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("eventos"); ?>
    <div class="content">
        <div class="title"><?= $label["Eventos"]?>
        <?php if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] != "cliente" || $_SESSION["app-user"]["permission"][$sectionId]["create"] == "1"){?>
            <a href="./manage_event.php" class="add"><?= $label["Añadir nuevo"]?></a>
        <?php } ?>
        </div>
        <?= $globalMessage ?>
        <?php if ($events){ ?>
        <?=       @$ui->buildTable($events) ?>
        <?php }else{ ?>
            -- <?= $label["No hay eventos disponibles"];?> --
        <?php }?>
    </div>
     <?= my_footer() ?>
  </body>
</html>                                                                                                                                                                        