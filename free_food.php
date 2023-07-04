<?php

define("URL_FREE_FOOD","http://www.freefood.sk/menu/#free-food");

$curl = curl_init(URL_FREE_FOOD);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$page_ff = curl_exec($curl);

$dom_ff = new DOMDocument();
libxml_use_internal_errors(true);
$dom_ff->loadHTML($page_ff);
libxml_use_internal_errors(false);

$dni_ff = [];
$nazvy_ff = [];
$cena_ff = [];
$typ_jedla_ff = [];

function fetchHTMLFreeFood($db){
    global $page_ff;
    $sql = "INSERT INTO html (name, html) VALUES (?,?)";
    $stmt = $db->prepare($sql);
    return $stmt->execute(["Free Food", $page_ff]);
}

function parseFreeFood(){
    global $dni_ff, $nazvy_ff, $cena_ff, $typ_jedla_ff, $dom_ff;
    $finder = new DomXPath($dom_ff);
    $class="day-title";

    $nodes = $finder->query("//span[@class='$class']");

    // DNI FREEFOOD

    $i = 0;
    foreach ($nodes as $node){
        //var_dump($node->textContent);
        if ($i==5){
            break;
        }
        for ($j=0;$j<4;$j++){
            $comma_position = strpos($node->textContent, ','); // find the position of the first comma
            $sub_string = substr($node->textContent, 0, $comma_position); // get the substring from the beginning to the comma
            $dni_ff[] = mb_convert_case($sub_string, MB_CASE_TITLE, "UTF-8");
        }
        $i++;
    }

    // TYP JEDLA
    $i = 0;
    $ul = $finder->query("//span[@class='brand']");
    foreach ($ul as $li) {
        if($i > 25){
            break;
        }elseif ($i <= 5){
            $i++;
            continue;
        }
        $typ_jedla_ff[] = trim($li->textContent, " ");

        //echo $li->textContent."<br>";
        // do something with $li
        $i++;
    }

    // NAZOV JEDLA
    $i = 0;
    foreach ($dom_ff->getElementsByTagName('li') as $li) {
        foreach ($li->childNodes as $node) {
            if ($i==20){
                break;
            }
            if ($node->nodeType == XML_TEXT_NODE) {
                $nazvy_ff[] = $node->nodeValue;
                $i++;
            }
        }
    }

    // CENY
    $i = 0;
    $ul = $finder->query("//span[@class='brand price']");
    foreach ($ul as $li) {
        if($i == 20){
            break;
        }
        $cena_ff[] = $li->textContent;
        //echo $li->textContent."<br>";
        // do something with $li
        $i++;
    }
}

function insertToDatabaseFreeFood($db): bool{
    global $dni_ff,$nazvy_ff,$typ_jedla_ff, $cena_ff;
    parseFreeFood();

    $sql = "INSERT INTO jedalne (nazov_jedalne,den, typ_jedla, nazov, cena) VALUES (?,?,?,?,?)";

    try{
        for ($i = 0; $i < count($nazvy_ff); $i++){
            $stmt = $db->prepare($sql);
            //var_dump($dni_ff[$i]);
            $stmt->execute(["Free food",strval($dni_ff[$i]),$typ_jedla_ff[$i],$nazvy_ff[$i],$cena_ff[$i]]);}
        return true;
    }catch (Exception $e){
        return false;
    }
}