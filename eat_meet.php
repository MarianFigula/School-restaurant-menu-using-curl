<?php

define("URL_EAT","http://eatandmeet.sk/tyzdenne-menu");


$curl = curl_init(URL_EAT);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$page_eat = curl_exec($curl);

$dom_eat = new DOMDocument();
libxml_use_internal_errors(true);
$dom_eat->loadHTML($page_eat);
libxml_use_internal_errors(false);

$dni = [];
$nazvy = [];
$cena = [];
$typ_jedla = [];
$obrazky = [];

function fetchHTMLEatMeet($db){
    global $page_eat;
    $sql = "INSERT INTO html (name, html) VALUES (?,?)";
    $stmt = $db->prepare($sql);
    return $stmt->execute(["Eat and Meet", $page_eat]);
}

function parseEatMeet(){
    global $dni, $nazvy, $cena, $typ_jedla, $obrazky, $dom_eat;
    $string = "";
    for ($counter = 1; $counter<=7; $counter++){
        $parsed = $dom_eat->getElementById('day-'.$counter);
        // NAZOV JEDLA
        $p = $parsed->getElementsByTagName('p');
        $span = $parsed->getElementsByTagName('span');
        $h4s = $parsed->getElementsByTagName("h4");

        $finder = new DomXPath($dom_eat);
        $ul = $finder->query("//ul[@class='tabs-link  white  text-center']");

        foreach ($p as $tag) {
            if ($tag->getAttribute('class') == 'desc') {
                $nazvy[] = $tag->nodeValue;
            }
        }

        switch ($counter){
            case 1: $string="Pondelok";break;
            case 2:$string="Utorok"; break;
            case 3:$string="Streda"; break;
            case 4:$string="Štvrtok";break;
            case 5:$string="Piatok";break;
            case 6:$string="Sobota";break;
            case 7:$string="Nedeľa";break;
        }

        foreach ($span as $tag) {
            if ($tag->getAttribute('class') == 'price') {
                $cena[] = trim($tag->nodeValue,' ');
            }
        }
        foreach ($h4s as $h4){
            $typ_jedla[] = $h4->textContent;
            $dni[] = $string;
        }
    }

    // IMG DO DATABAZY
    $xpath = new DOMXPath( $dom_eat );
    $nodes = $xpath->query( "//img[@class='img-responsive center-block']" );
    for( $j = 0; $j < count($typ_jedla); $j++ ) {
        $obrazky[] = $nodes->item($j)->getAttribute("src");
    }
}

function insertToDatabaseEatMeet($db): bool
{
    global $dni, $nazvy, $cena, $typ_jedla, $obrazky;
    parseEatMeet();
    $sql = "INSERT INTO jedalne (nazov_jedalne ,den, typ_jedla, nazov, cena, img) VALUES (?,?,?,?,?,?)";
    try{
        for ($i = 0; $i < count($nazvy); $i++){
            $stmt = $db->prepare($sql);
            $stmt->execute(["Eat and meet",$dni[$i], $typ_jedla[$i],$nazvy[$i],$cena[$i], $obrazky[$i]]);
        }
        return true;
    }catch (Exception $e){
        return false;
    }
}