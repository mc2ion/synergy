<?php
/*
    Author: Marion Carambula
    Sección de usuarios
*/
include ("./common/common-include.php");
//Verificar que el usuario tiene  permisos
$sectionId = "8";
if ($_SESSION["app-user"]["user"][1]["type"] == "cliente" && $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0"){ header("Location: ./index.php?e={$globalEventId}&c={$globalClientId}"); exit();}


$review     = $backend->getReviewList($input["review"]["list"]["no-show"]);
//$pagination = $ui->buildPagination(@$_GET["fireUI"]["currentPage"],$backend->app["count"]/$backend->app["perPage"]);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("evaluaciones"); ?>
    <div class="content">
        <div class="title"><?= $label["Evaluaciones"]?> </div>
        <?= $globalMessage ?>
        <?php if ($review){ ?>
        <?=       @$ui->buildTable($review, 1,1) ?>
        <?php }else{ ?>
                -- <?= $label["No hay evaluaciones disponibles"];?> --
        <?php }?>
    </div>
  </body>
</html>                                                                                                                                                                        