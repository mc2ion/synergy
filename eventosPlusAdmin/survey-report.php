<?php
/*
    Author: Marion Carambula
    SecciÃ³n de encuestas
*/
include ("./common/common-include.php");
//Verificar que el usuario tiene  permisos
if ($typeUser[$_SESSION["app-user"]["user"][1]["type"]] == "cliente" && $_SESSION["app-user"]["permission"]["9"]["read"] == "0"){ header("Location: ./index.php"); exit();}


$out = "";
$surveys = $backend->getSurveyReport($_SESSION["data"]["evento"]);
$event   = $backend->getEvent($_SESSION["data"]["evento"]);
$title   = $event["name"];


if (isset($_POST["pdf"])&& $surveys){
        require_once("./backend/dompdf/dompdf_config.inc.php");
        $htmlString = '';
        $htmlString = utf8_decode(getReport($title, "1"));
        $dompdf = new DOMPDF();
        $dompdf->set_paper("letter", "portrait");
        $dompdf->load_html($htmlString);
        $dompdf->render();
        $dompdf->stream($title.".pdf");
        exit(0);    
}

if (isset($_POST["cvs"])&& $surveys){
    $out = "";
    foreach($surveys["survey"] as $k=>$v){
        $out .= $v["question"]  . "\n";
        foreach($v["option"] as $sk=>$sv){
            $resl = "resultado";
            if ($sv["result"] > 1) $resl = "resultados";
            $out .= $sv["option"].', '.$sv["result"] . " ". $resl . "\n";
        }
    }

    //Generate the CSV file header
    header("Content-type: application/vnd.ms-excel");
    header("Content-Encoding: UTF-8");
    header("Content-type: text/csv; charset=UTF-8");
    header("Content-disposition: csv" . date("Y-m-d") . ".csv");
    header("Content-disposition: filename=".$title.".csv");
    echo "\xEF\xBB\xBF"; // UTF-8 BOM
    //Print the contents of out to the generated file.
    print $out;

    //Exit the script
    exit;
}

function getReport($title, $pdf=""){
        global $surveys, $label;
        $out = '<style>
                   h1{font-size:16px; font-weight:normal;}
                   .ttq td.title { background-color: #545454; color:white; padding: 5px; border: 1px solid black;}
                   table.ttq {width: 100%;border: 1px solid gray;  border-collapse: collapse;  text-align: center;  table-layout: fixed; margin-top:30px;}
                   .ttq td   {border: 1px solid;  padding: 4px 0px;}
                   .title    {margin-bottom:10px;}
                   .report-logo{vertical-align:middle; width: 100px; position: relative;  top: 2px;}
                   .client_logo{vertical-align: middle; display: inline-block; margin-right: 10px;}
                   .client_logo img {max-width: 120px; max-height: 90px; width: 120px;}
                   .eventosplus img{width: 100px;}
                   .div-title {display: inline-block; font-size:18px; font-weight:normal;}
                    .footer { background: #989898; color:white; width: 100%; margin-bottom: 30px;}';
        if ($pdf == ""){
                $out .= ".footer {display:none;}";
                $out .= ".client_logo img{ width: inherit !important; }";
        }
        $out .= '</style>';
        if ($surveys["survey"]){
            $out  .= footer_report();
            $out .= '<table class="ttq-logo">';
            $out .= "<tr>
                        <td><div class='client_logo'><img src='{$surveys["logo_path"]}'  alt='Logo cliente'/></div></td>
                        <td><div class='div-title'> {$label["Resultado de encuestas"]} -  $title </div></td>
                     </tr>";
            $out .= '</table>';
            $out .= '<table class="ttq">';
            foreach($surveys["survey"] as $k=>$v){
                $out .='<tr>
                            <td class="title" colspan="2"><h1>'.$v["question"].'</h1></td>
                        </tr>';
                foreach($v["option"] as $sk=>$sv){
                    $resl = "resultado";
                    if ($sv["result"] > 1) $resl = $label["resultados"];
                $out .='<tr>
                            <td>'.$sv["option"].'</td><td>'.$sv["result"].' ' . $resl .' </td>
                        </tr>';
                }
            }
            $out .= '</table>';
        }else{
            $out .= '-- '. $label["Esta encuesta no posee resultados aÃºn"]. ' --';
        }
        return $out;
}

?>

<!DOCTYPE html>
<html lang="es">
  <head>
     <?= my_header()?>
     <link rel="stylesheet" href="./css/surveyprint.css" type="text/css" media="print" />
  </head>
  <body>
    <?= menu("encuestas"); ?>
    <div class="content">
        <?= $globalMessage ?>
        <?php if ($surveys) { ?>
            <div class="actions">
            <form method="post">
                <input type="submit" class='add' name="cvs"     value="<?= $label["Exportar Excel"]?>"/>
                <input type="submit" class='add' name="pdf"     value="<?= $label["Exportar PDF"]?>"/>
                <a href="javascript:window.print()" class="add"><?= $label["Imprimir"]?></a>
            </form>
            </div>
        <?php } ?>
       <?= getReport($title)?>
       <div style="margin-top: 15px; text-align: right;" class="action">
        <a href="./surveys.php"><?= $label["Volver"]?></a>
       </div>
    </div>
     <?= my_footer() ?>
  </body>
</html>                                                                                                                                                                