<?php
/*
    Author: Marion Carambula
    Sección de eventos
*/
include ("./common/common-include.php");
$out = "";
$sectionId = "9";
//Verificar que el usuario tiene  permisos
if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" && $_SESSION["app-user"]["permission"][$sectionId]["read"] == "0"){ header("Location: ./index.php"); exit();}

$surveys  = $backend->getSurveyQuestionList($_SESSION["data"]["evento"], $input["survey"]["list"]["no-show"]);

?>

<!DOCTYPE html>
<html lang="es">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("encuestas"); ?>
    <div class="content">
        <div class="title"><?= $label["Encuesta | Preguntas"]?> 
         <?php if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "administrador" || $_SESSION["app-user"]["permission"][$sectionId]["create"] == "1"){?>
             <a href="./manage_question.php" class="add"><?= $label["Añadir nueva"]?></a>
        <?php } ?>
        </div>
        <?= $globalMessage ?>
        <?php if ($surveys){ ?>
            <div class="actions">
                <a href="survey-report.php" class="add"><?= $label["Reporte"]?></a>
            </div>
        <div style="clear:both"></div>
        <?=       @$ui->buildTable($surveys, 0,0) ?>
        <?php }else{ ?>
                -- <?= $label["No hay preguntas disponibles"];?> --
        <?php }?>
    </div>
     <?= my_footer() ?>
  </body>
</html>                                                                                                                                                                