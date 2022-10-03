
<?php

require('../html_dom/simple_html_dom.php');
require("../db/db.php");
ini_set('post_max_size', '5M');

set_time_limit(600);

$base_url = $_POST['url'];

$already_crawled = array();
$crawling = array();

function get_details($url)
{

    global $con;


    $options = array('http' => array('method' => "GET", 'headers' => "User-Agent: howBot/0.1\n"));

    $context = stream_context_create($options);

    $doc = new DOMDocument();

    @$doc->loadHTML(@file_get_contents($url, false, $context));

    if (substr($url, 0, 4) === "http") {
        // HREF SCRAPPER
        $scheme = parse_url($url, PHP_URL_SCHEME);
        $user = parse_url($url, PHP_URL_USER);
        $pass = parse_url($url, PHP_URL_PASS);
        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);
        $path = parse_url($url, PHP_URL_PATH);
        $query = parse_url($url, PHP_URL_QUERY);
        $fragment = parse_url($url, PHP_URL_FRAGMENT);

        //echo ' FN: '. basename($href);                        // GET FILE NAME WITH EXTENSION
        //echo ' EX: '. pathinfo($href, PATHINFO_EXTENSION);    // GET EXTENSION
        //echo ' FNE: '. pathinfo($href, PATHINFO_FILENAME);    // GET FILE NAME

        // SET DATE TIMEZONE & GET CURRENT DATE
        date_default_timezone_set("Europe/Lisbon");
        $year = date("Y");
        $month = date("m");
        $day = date("d");

        // QUERY INSERT HREF DATA ON TABLE PAGES 
        $query_insert_pages = "INSERT INTO pagina(host, path, year, month, day)
                         VALUES ('$host','$path','$year','$month', '$day');";
        $con->query($query_insert_pages);
    }

    $html = file_get_html($url);
    $text = strip_tags($html);
    $text = preg_replace("#[[:punct:]]#", " ", $text);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $text);
    $text = preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);

    $title = '';
    $titleTags = $doc->getElementsByTagName('title');
    if (count($titleTags) > 0) {
        $title = mysqli_real_escape_string($con, $titleTags[0]->nodeValue);
    }

    $query_content = "INSERT INTO infcontida (title, textt, path) 
                        VALUES ('$title', '$text', '$path');";

    $con->query($query_content);
    //$text = base64_decode($text);
}//fim da função get_details



function follow_links($con, $url)
{
    global $already_crawled;
    global $crawling;

    $options = array('http' => array('method' => "GET", 'headers' => "User-Agent: howBot/0.1\n"));

    $context = stream_context_create($options);

    $doc = new DOMDocument();

    @$doc->loadHTML(@file_get_contents($url, false, $context));
    $linklist = $doc->getElementsByTagName("a");
    foreach ($linklist as $link) {
        $l = $link->getAttribute("href");
        if (substr($l, 0, 1) == "/" && substr($l, 0, 2) != "//") {
            $l = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . $l;
        } else if (substr($l, 0, 2) == "//") {
            $l = parse_url($url)["scheme"] . ":" . $l;
        } else if (substr($l, 0, 2) == "./") {
            $l = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . dirname(parse_url($url)["path"]) . substr($l, 1);
        }
        # else if (substr($l, 0, 1) == "#") {
        #     $l = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . parse_url($url)["path"] . $l;
        # }
        else if (substr($l, 0, 3) == "../") {
            $l = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . "/" . $l;
        } else if (substr($l, 0, 11) == "javascript:") {
            continue;
        } else if (substr($l, 0, 5) != "https" && substr($l, 0, 4) != "http") {
            $l = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . "/" . $l;
        }

        if (!in_array($l, $already_crawled)) {
            $already_crawled[] = $l;
            $crawling[] = $l;


            get_details($l) . "\n";
        }
        save_images($con, $doc);
    }
} //fim da função follow_links

function insert_array($con, $crawling)
{
    $count = 0;
    foreach ($crawling as $site) {
        if ($site != NULL) {
            follow_links($con, $site);
        }
    }
}//Fim da função insert_array

function save_images($con, $doc)
{
    // IMAGES_________________________________________________________________

    $imgs_in_page = $doc->getElementsByTagName("img");
    $img_counter = 0;
    foreach ($imgs_in_page as $imgs) {
        $src_of_img = $imgs->getAttribute("src");

        $ch = curl_init($src_of_img);

        $my_save_dir = '../imagens/';
        $filename = basename($src_of_img);
        $complete_save_loc = $my_save_dir . $filename;

        $fp = fopen($complete_save_loc, 'wb');

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        open_images($con);
    }
}// Fim da função save_images

function open_images($con)
{
    $dirname = "../imagens/";
    $images = glob($dirname . "*.{jpg,gif,png,svg}", GLOB_BRACE);
    #$items= array();

    foreach ($images as $image) {
        $img = base64_encode($image);
        $query_insert_images = "INSERT INTO imagens (img, id_pagina)
        SELECT * FROM (SELECT '$img', NULL) AS tmp
        WHERE NOT EXISTS (
            SELECT img FROM imagens WHERE img = '$img'
        );";

        $con->query($query_insert_images);
    }
} //Fim da função open_images

follow_links($con, $base_url);

?>