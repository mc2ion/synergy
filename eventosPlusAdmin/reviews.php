<?php
/*
    Author: Marion Carambula
    Secci�n de evaluaciones
*/
include ("./common/common-include.php");
//Verificar que el usuario tiene  permisos
$sectionId = "6";
if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" && $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0"){ header("Location: ./index.php"); exit();}
if (!isset($_SESSION["data"]["evento"])) {header("Location: ./index.php"); exit();}

$review     = $backend->getReviewList($input["review"]["list"]["no-show"]);

?>

<!DOCTYPE html>
<html lang="es">
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("evaluaciones"); ?>
    <div class="content">
        <div class="title"><?= $label["Evaluaciones"]?> </div>
        <?= $globalMessage ?>
        <?php if ($review){ ?>
        <?=       @$ui->buildTable($review) ?>
        <?php }else{ ?>
                -- <?= $label["No hay evaluaciones disponibles"];?> --
        <?php }?>
    </div>
     <?= my_footer() ?>
  </body>
</html>                                                                                                                                                                        