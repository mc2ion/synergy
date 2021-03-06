<?php
/*
    Author: Marion Carambula
    SecciÃ³n de clientes
*/
include ("./common/common-include.php");
//Verificar que el usuario tiene  permisos
$sectionId = "1";
if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" && $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0"){ header("Location: ./index.php"); exit();}

$clients    = $backend->getClientList($input["client"]["list"]["no-show"], 0, $typeUser[$_SESSION["app-user"]["user"][1]["type"]]);

unset($_SESSION["client"]["logo_path"]);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
      <?= my_header()?>
  </head>
  <body>
    <?= menu("clientes"); ?>
    <div class="content">
        <div class="title"><?= $label["Clientes"]?> 
        <?php if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "administrador"
            || ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" &&
        @$_SESSION["app-user"]["permission"][$sectionId]["create"] == "1")){?>
        <a href="./manage_client.php" class="add"><?= $label["Añadir nuevo"]?></a>
        <?php } ?>
        </div>
        <?= $globalMessage ?>
        <?php if ($clients){?>
            <?=       @$ui->buildTable($clients) ?>
         <?php }else{ ?>
                -- <?= $label["No hay clientes disponibles"];?> --
        <?php }?>
    </div>
    <?php if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente-administrador"){?>
        <style>
            .dataTables_filter { display:none !important;}
        </style>
    <?php } ?>
     <?= my_footer() ?>
  </body>
</html>                                                                                                                                                                        