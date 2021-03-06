<?php
/*
    Author: Marion Carambula
    Sección de speakers
*/
include ("./common/common-include.php");
$sectionId = "8";
//Verificar que el usuario tiene  permisos
if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" && $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0"){ header("Location: ./index.php"); exit();}


$speakers   = $backend->getSpeakerList($_SESSION["data"]["evento"], $input["speaker"]["list"]["no-show"]);
if (!isset($_SESSION["data"]["evento"])) {header("Location: ./index.php"); exit();}
unset($_SESSION["speaker"]["image_path"]);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("presentadores"); ?>
    <div class="content">
        <div class="title"><?= $label["Presentadores"]?> 
         <?php if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] != "cliente" || $_SESSION["app-user"]["permission"][$sectionId]["create"] == "1"){?>
              <a href="./manage_speaker.php" class="add"><?= $label["Añadir nuevo"]?></a>
        <?php } ?>
        </div>
        <?= $globalMessage ?>
        <?php if ($speakers){ ?>
        <?=       @$ui->buildTable($speakers, 1,1) ?>
        <?php }else{ ?>
                -- <?= $label["No hay presentadores disponibles"];?> --
        <?php }?>
    </div>
     <?= my_footer() ?>
  </body>
</html>                                                                                                                                                                        