<?php
/*
    Author: Marion Carambula
    Manejo de preguntas (encuestas)
*/
include ("./common/common-include.php");
//Verificar que el usuario tiene  permisos
$sectionId = "9"; $read = 0;

$read       = verify_permissions($sectionId, "surveys.php");

//Obtener las columnas a editar/crear
$columns = $backend->getColumnsTable("survey_question");
$id      =  $message      =  $error = "";
$section = "survey";
$eventId = $_SESSION["data"]["evento"];

//Agregar nueva pregunta
if (isset($_POST["add"]) || isset($_POST["edit"])){
    $en["event_id"] = $eventId;
    $questionId     = @$backend->clean($_POST["id"]);
    foreach ($columns as $k=>$v) {
        if (isset($input[$section]["manage"]["mandatory"])){
            //Verifico que los elementos mostrados en el formulario
            if (!in_array($v["COLUMN_NAME"],$input[$section]["manage"]["no-show"])){
                // Verifico los obligatorios
                if ($input[$section]["manage"]["mandatory"] == "*" || in_array($v["COLUMN_NAME"], $input[$section]["manage"]["mandatory"])){
                    if ($_POST[$v["COLUMN_NAME"]] == "") {
                        $error =  1;
                        $missing[$v["COLUMN_NAME"]] = 1;
                    }else $en[$v["COLUMN_NAME"]] = $backend->clean($_POST[$v["COLUMN_NAME"]]);
                }else{
                    $en[$v["COLUMN_NAME"]] =  $backend->clean($_POST[$v["COLUMN_NAME"]]);
                }
                //Respuesta
                foreach ($_POST["value_option"] as $k=>$v){
                    $options[$k+1]["optionDesc"] =  $v;
                    if ($v == "") $error = 1;
                    $missing["options"]  = 1;
                }
            }
       }
   }
   if (!$error){
       if (isset($_POST["add"])){
            //1. Agregar pregunta
            $questionId = $backend->insertRow("survey_question", $en);
            if ($questionId){
                //2. Agregar las respuestas 
                foreach($_POST["value_option"] as $k=>$v){
                    $option["question_id"]          = $questionId;
                    $option["optionDesc"]           = $v;
                    $id = $backend->insertRow("question_option", $option);
                    if ($id < 0) $errorQ = 1;
               }
               if (!$errorQ){
                    $_SESSION["message"]  = "<div class='succ'>".$label["Pregunta agregada exitosamente"] ."</div>";
                    header("Location: ./surveys.php");
                }
            }
       }else{
            //Verificar opciones nuevas y eliminadas
           // 1. Opciones que tenia antes  
           $options        = $backend->getOptions($questionId);
           $active         = $_POST["active"];
           foreach($active as $k=>$v){
                 $option_id[$v] = $v;
           }
           
           //2. Eliminadas
           foreach($options as $k=>$v){
                if (!in_array($v["option_id"], $option_id)) {
                    $enO["active"] = "0";
                    $id = $backend->updateRow("question_option", $enO, " option_id = '{$v["option_id"]}' ");
                }
           }
           //3. Modificadas  y creadas 
           foreach($_POST["value_option"] as $k=>$v){
                $option["question_id"]          = $questionId;
                $option["optionDesc"]           = $v;
                $active                         = $_POST["active"][$k];
                //Modificadas
                if ($active != ""){
                    $id = $backend->updateRow("question_option", $option, " option_id = '{$active}' ");
                }
                //Creadas
                else{
                   $id = $backend->insertRow("question_option", $option);
                }
           }
           
           $id = $backend->updateRow("survey_question", $en, " question_id = '$questionId' ");
           if ($id > 0) { 
                $_SESSION["message"]  = "<div class='succ'>".$label["Pregunta editada exitosamente"] ."</div>";
                header("Location: ./surveys.php");
           }else{
                $message = "<div class='error'>".$label["Hubo un problema con la edición"]. "</div>";
           }
       }
   }else{
        $message   = "<div class='error'>".$label["Por favor ingrese todos los datos requeridos"]. "</div>";
        $survey    = $en;
        
   }
}
//Borrar Pregunta
if (isset($_POST["delete"])){
   $id              = $backend->clean($_POST["id"]);
   $en["active"]    = "0";
   $idR = $backend->updateRow("survey_question", $en, " question_id = '$id' ");

    //Borrar respuestas asociadas
   $enA["active"]   = "0";
   $backend->updateRow("question_option", $enA, " question_id = '$id' ");

    if ($idR > 0) {
        $_SESSION["message"] = "<div class='succ'>".$label["Pregunta borrada exitosamente"] ."</div>";
        header("Location: ./surveys.php");
   }else{
        $message = "<div class='error'>".$label["Hubo un problema con el borrado"]. "</div>";
   }
}

/** Fin acciones **/

//Si el parametro id esta definido, estamos editando la entrada
if (isset($_GET["id"]) && $_GET["id"] > 0 ){
    $id             = $backend->clean($_GET["id"]);
    if ($read)      $title          = $label["Ver Pregunta"];
    else            $title          = $label["Editar Pregunta"];
    $action         = "edit";
    if (!$error){
        $survey         = $backend->getSurvey($id);
        //Obtener respuestas asociadas
        $options        = $backend->getOptions($id);
    }
    
}else{
    $title = $label["Crear Pregunta"];
    $action = "add";
    $options = array("1"=>array("optionDesc"=>""));
}


$label["client_id"]             = "Cliente";
$clients                        = $backend->getClientList(array(), "1");
?>

<!DOCTYPE html>
<html lang="es">
  <head>
     <?= my_header()?>
  </head>
  <body>
    <?= menu("encuestas"); ?>
    <div class="content">
        <div class="title-manage"><?= $title?></div>
        <?=$message ?>
        <form id="form" method="post" enctype="multipart/form-data">
            <?php if ($action == "edit") {?>
                <input type="hidden" name="img" value="<?=  $survey["image_path"]?>" />
                <input type="hidden" name="id"  value="<?=  $_GET["id"]?>" />
            <?php } ?>
            <table class="manage-content">
            <?php foreach ($columns as $k=>$v) {
                    $mandatory =  $readOnly = "";
                    if (!in_array($v["COLUMN_NAME"],$input["$section"]["manage"]["no-show"])){
                        if ($read) $readOnly = "disabled";
                        $type  = (isset($input["$section"]["manage"][$v["COLUMN_NAME"]]["type"])) ? $input["$section"]["manage"][$v["COLUMN_NAME"]]["type"] :  "";
                        $value = (isset($survey[$v["COLUMN_NAME"]])) ? $survey[$v["COLUMN_NAME"]] : "";
                        if ($input[$section]["manage"]["mandatory"] == "*") $mandatory = "(<img src='images/mandatory.png' class='mandatory'>)";
                        else if (in_array($v["COLUMN_NAME"], $input[$section]["manage"]["mandatory"])) $mandatory = "(<img src='./images/mandatory.png' class='mandatory'>)";
                ?>
                <?php // Se hace la verificacion del tipo del input para cada columna ?>
                    <tr class="tr_<?=$v["COLUMN_NAME"]?>">
                        <td class="tdf"><?=(isset($label[$v["COLUMN_NAME"]])) ? $label[$v["COLUMN_NAME"]]: $v["COLUMN_NAME"]?> <?= $mandatory?>:</td>
                        <td>
                <?php // Tipo por defecto. Se muestra un input text?>
                <?php   
                        if ($type == ""){ 
                ?>
                       <input type="text" name="<?= $v["COLUMN_NAME"]?>" value="<?= $value ?>" <?= $readOnly?> />
                 <?php // Tipo File. Se muestra un input file ?>
                 <?php } else if ( $type == "file") { ?>
                        <?php if ($value != "") {?>
                            <img class='manage-image' src='./<?=$value?>'/>
                        <?php } ?>
                        <?php if (!$read) { ?>
                            <input type="file" name="<?= $v["COLUMN_NAME"]?>" />
                        <?php  } ?>
                 <?php // Tipo textarea. Se muestra un textarea ?>
                 <?php } else if ($type == "textarea") { ?>
                        <textarea name="<?= $v["COLUMN_NAME"]?>" <?= $readOnly?> ><?=$value?></textarea>
                 <?php // Tipo date. Se muestra un text pero especial para tener el date picker ?>
                 <?php } else if ($type == "date") { ?>
                        <input type="text" class="datepicker" name="<?= $v["COLUMN_NAME"]?>" value="<?=$value?>" <?= $readOnly?> />
                 <?php // Tipo select. Se muestra un select con sus opciones ?>
                 <?php } else if ($type == "select") { ?>
                            <select name="<?= $v["COLUMN_NAME"]?>" <?= $readOnly?> >
                                <?php foreach ($clients as $sk=>$sv){?>
                                    <option value="<?=$sv["client_id"]?>"><?= $sv["name"]?></option>
                                <?php }?>
                            </select>
                  
                 <?php } ?>
                    <div class="missing-error">
                        <?php if (isset($missing[$v["COLUMN_NAME"]])) { ?>
                            <?= $label["Este campo es obligatorio"]?>
                        <?php } ?>
                     </div>
                     </td>
                 </tr>
                 <?php                        
                    }
                }
            ?>
            <?php //Añadir seccion de opciones  
            $mandatory = "(<img src='images/mandatory.png' class='mandatory'>)";
            if ($read) $readOnly = "disabled";
            ?>
            <tr>
                <td colspan="2" class="options-title"><?= $label["Respuestas"]?> <?= $mandatory?></td>
            </tr>
            <tr>
                <td class="option-td" colspan="2">
                    <?php if (!$read) {?>
                        <div class="add-opt"><a href="javascript:void(0)"><?= $label["Agregar nueva"]?></a></div>
                    <?php } ?>
                    <?php foreach ((array)$options as $mk=>$mv){
                        $value      = $mv["optionDesc"];
                        $optId = (isset($mv["option_id"])) ? $mv["option_id"]: "";
                    ?>
                        <div class="option">
                            <div class="opt-desc">
                                <div class="label"><?= $label["Opción"]?> <?=$mandatory?>:</div>
                                <div class="value"><textarea name="value_option[]" <?= $readOnly?> ><?= $value?></textarea></div>
                            </div>
                            <?php if (!$read) {?>
                            <div class="delete-opt i<?= $mk - 1 ?>"><a href="javascript:void(0)"><?= $label["Eliminar"]?></a></div>
                            <?php } ?>
                            <div class="hidden"><input type="hidden" name="active[]" value="<?= $optId?>"/></div>
                        </div>
                    <?php } ?>
                    <div class="missing-error">
                    <?php if (isset($missing["options"])) { ?>
                                <?= $label["Este campo es obligatorio"]?>
                    <?php } ?>
                    </div>
               </td>
            </tr>
            <tr>
                <td></td>
                <td class="action">
                    <?php if (!$read) { ?>
                    <input type="submit" name="<?= $action?>" value="<?= $label["Guardar"]?>" />
                    <?php } ?>
                    <?php if ($action == "edit" && ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] != "cliente" || $_SESSION["app-user"]["permission"][$sectionId]["delete"] == "1")){?>
                        <input type="button" class="important dltP" name="delete" value="<?= $label["Borrar"]?>" />
                    <?php } ?>
                    <a href="./surveys.php"><?= $label["Volver"]?></a>
                </td>
            </tr>
            
            </table>
            
        </form>
    </div>
     <?= include('common/dialog.php'); ?>
     <?= my_footer() ?>
  </body>
</html>