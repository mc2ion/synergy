<?php
/*
    Author: Marion Carambula
    Sección de sesiones
*/
include ("./common/common-include.php");

//Verificar que el usuario tiene  permisos
$sectionId = "4";
if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" && $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0"){ header("Location: ./index.php"); exit();}
if (!isset($_SESSION["data"]["evento"])) {header("Location: ./index.php"); exit();}

$out     = "";
$session    = $backend->getSessionList($_SESSION["data"]["evento"], $input["session"]["list"]["no-show"]);
$rooms      = $backend->getRoomList($_SESSION["data"]["evento"], array(), "1");

unset($_SESSION["session"]["image_path"]);

?>

<!DOCTYPE html>
<html lang="es">
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("sesiones"); ?>
    <div class="content">
        <div class="title"><?= $label["Sesiones"]?>
         <?php if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] != "cliente"|| $_SESSION["app-user"]["permission"][$sectionId]["create"] == "1"){?>
            <?php //Verificar que haya al menos una sala creada para poder añadir una nueva sesión 
                if (count($rooms) > 0 && $rooms != ""){
            ?>
             <a href="./manage_session.php" class="add"><?= $label["Añadir nueva"]?></a>
            <?php }else{ ?> 
                <div><?= $label["Para agregar una sesión, debe tener creado al menos una sala."]?> <a class="add" href="./manage_room.php">Crear Sala</a></div>
            <?php } ?>
        <?php } ?>
        </div>
        
        <?= $globalMessage ?>
        <?php if ($session){ ?>
        <?=       @$ui->buildTable($session) ?>
        <?php }else{ ?>
                -- <?= $label["No hay sesiones disponibles"];?> --
        <?php }?>
    </div>
     <?= my_footer() ?>
  </body>
</html>                                                                                                                                                                        