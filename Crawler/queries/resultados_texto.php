<?php
require("../db/db.php");

$con;
$search=$_POST['pesquisa'];

query_text($search);

function query_text($keyword){
    global $con;
    $query = "SELECT textt FROM infcontida i, pagina p  WHERE i.id = p.id AND textt LIKE '%$keyword%'" ;
    $resultado = mysqli_query($con,$query) or die("Erro ao retornar dados");
    echo $html = '<!DOCTYPE html>';
    echo $html = '<html>';
    echo $html = '<head>';
    echo $html = '<title>Procura</title>';
    echo $html = '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
    echo $html = '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pathway+Gothic+One" />';
    echo $html = '<link href="../css/cssmenu.css" rel="stylesheet" />';
    echo $html = '</head>';
    echo $html = '<body>';
    echo $html = '<div id="menuDemo">';
    echo $html = '<!--start CssMenu-->';
    echo $html = '<div id="cssmenu">';
    echo $html = '<ul>';
    echo $html = '<li>';
    echo $html = '<span>CRAWLER</span>';
    echo $html = '</li>';
    echo $html = '</ul>';
    echo $html = '</div>';
    
    while ($registro = mysqli_fetch_array($resultado))
    {
      if ($registro != NULL){ 
         
        echo $html = '<div class="text-search">';
        echo $html = '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pathway+Gothic+One" />';
        echo $html = '<link href="../css/cssmenu.css" rel="stylesheet" />';
        echo $html = '</head>';
        echo $html = '<body>';
        echo $html = '<div id="menuDemo">';
        echo $html = '<!--start CssMenu-->';
        echo $html = '<div id="cssmenu">';
        echo $html = '<ul style="background-color:#e55e39;">';
        echo $html = '<li>';
        echo $html = '<span style="font-size:25px;">Dados Obtidos</span>';
        echo $html = '</li>';
        echo $html = '</ul>';
        echo $html = "<label id='label-text'>".$registro['textt']."<label>";
        echo $html = '</div>';
        echo $html = '</div>';
        echo $html = '</div>';
        echo $html = '</body>';
        echo $html =  '</html>'; 
    }else{
      echo "<p>Não foi possivel encontrar o conteudo no texto</p>";
    }
     
     
}
}
?>


