<?php
echo $html = '<!DOCTYPE html>';
echo $html = '<html>';
echo $html = '<head>';
echo $html = '<title>Crawler</title>';
echo $html = '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
echo $html = '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pathway+Gothic+One" />';
echo $html = '<link href="./css/cssmenu.css" rel="stylesheet" />';
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
echo $html = '<!--end CssMenu-->';
echo $html = '</div>';
echo $html = '<div class="form-container">';
echo $html = '<form class="form-container" action="crawler/crawler.php" method="post">';
echo $html = '<div class="strong-class">';
echo $html = '<strong id="strong-pesquisa">Pequisar</strong></br>';
echo $html = '</div>';
echo $html = '<input id="input-text" placeholder="Insira uma uma url" type="text" name="url">';
echo $html = '<input id="btn-sub" type="submit" name="submit" value="Go!">';
echo $html = '</div>';
echo $html = '</div>';
echo $html = '</body>';
echo $html =  '</html>';

?>
