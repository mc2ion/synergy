<?php
/*
    Author: Marion Carambula
    Sección de categorías
*/
include ("./common/common-include.php");
//Verificar que el usuario tiene  permisos
$sectionId = "10";
if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" && @$_SESSION["app-user"]["permission"][$sectionId]["read"] == "0"){ header("Location: ./index.php"); exit();}
if (!isset($_SESSION["data"]["evento"])) {header("Location: ./index.php"); exit();}

$client      = $_SESSION["data"]["cliente"];
$categories  = $backend->getCategoryList($client, $input["category"]["list"]["no-show"]);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("categorias"); ?>
    <div class="content">
        <div class="title"><?= $label["Categorias"]?> 
         <?php if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] != "cliente" || @$_SESSION["app-user"]["permission"][$sectionId]["create"] == "1"){?>
        <a href="./manage_category.php" class="add"><?= $label["Añadir nueva"]?></a>
        <?php } ?>
        </div>
        <?= $globalMessage ?>
        <?php if ($categories) { ?>
        <?= @$ui->buildTable($categories)?>
        <?php }else{ ?>
            -- <?= $label["No hay categorias disponibles"];?> --
        <?php }?>
    </div>
     <?= my_footer() ?>
  </body>
</html>                                                                                                                                                                        