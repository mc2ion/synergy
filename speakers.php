<?php
/*
    Author: Marion Carambula
    Sección de speakers
*/
include ("./common/common-include.php");
$sectionId = "7";
//Verificar que el usuario tiene  permisos
if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" && $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0"){ header("Location: ./index.php"); exit();}


$speakers   = $backend->getSpeakerList($input["speaker"]["list"]["no-show"]);
$pagination = $ui->buildPagination(@$_GET["fireUI"]["currentPage"],$backend->app["count"]/$backend->app["perPage"]);


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
         <?php if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "administrador" || $_SESSION["app-user"]["permission"][$sectionId]["create"] == "1"){?>
              <a href="./manage_speaker.php" class="add"><?= $label["Añadir nuevo"]?></a>
        <?php } ?>
        </div>
        <?= $globalMessage ?>
        <?php if ($speakers){ ?>
        <?=       @$ui->buildTable($speakers, 1,1) ?>
        <?=       $pagination ?>
        <?php }else{ 
                if (isset($_GET["fireUI"]["filter"])) {?>
                    <?= $label["No hay resultados que coincidan con su búsqueda"]?>. <a href="<?= @$_SERVER['HTTP_REFERER'] ?>" class="back"><?= $label["Volver"]?></a>
                <?php }else{ ?>
                -- <?= $label["No hay presentadores disponibles"];?> --
                 <?php } ?>
        <?php }?>
    </div>
     <?= my_footer() ?>
  </body>
</html>                                                                                                                                                                        