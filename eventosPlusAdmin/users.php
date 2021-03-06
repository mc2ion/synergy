<?php
/*
    Author: Marion Carambula
    Sección de usuarios
*/
include ("./common/common-include.php");
//Verificar que el usuario tiene  permisos
$sectionId = "2";

if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" && (!isset($_SESSION["app-user"]["permission"][$sectionId]["read"]) || $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0")){ header("Location: ./index.php"); exit();}

$out        = "";
$users      = $backend->getUserList($input["user"]["list"]["no-show"], $typeUser[$_SESSION["app-user"]["user"][1]["type"]] );
unset($_SESSION["user"]["photo_path"]);

?>

<!DOCTYPE html>
<html lang="es">
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("usuarios"); ?>
    <div class="content">
        <div class="title"><?= $label["Usuarios"]?>
        <?php if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] != "cliente" || @$_SESSION["app-user"]["permission"][$sectionId]["create"] == "1"){?>
         <a href="./manage_user.php" class="add"><?= $label["Añadir nuevo"]?></a>
        <?php } ?>
        </div>
        <?= $globalMessage ?>
        <?php if ($users){ ?>
        <?=       @$ui->buildTable($users) ?>
        <?php }else{ ?>
                -- <?= $label["No hay usuarios disponibles"];?> --
        <?php }?>
    </div>
     <?= my_footer() ?>
  </body>
</html>                                                                                                                                                                        