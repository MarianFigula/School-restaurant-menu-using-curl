<?php

define("URL_DRAG","https://www.restauracia-drag.sk/denne-menu" );

$curl = curl_init(URL_DRAG);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$page_drag = curl_exec($curl);

$dom_drag = new DOMDocument();
libxml_use_internal_errors(true);
$dom_drag->loadHTML($page_drag);
libxml_use_internal_errors(false);


$dni_drag = [];
$nazvy_drag = [];
$cena_drag = [];
$typ_jedla_drag = [];

function fetchHTMLDrag($db){
    global $page_drag;
    $sql = "INSERT INTO html (name, html) VALUES (?,?)";
    $stmt = $db->prepare($sql);
    return $stmt->execute(["Drag", $page_drag]);
}

function parseDrag($dom){
    global $dni_drag, $nazvy_drag, $cena_drag, $typ_jedla_drag;
    for ($i = 1; $i<=5; $i++){
        switch ($i){
            case 1:$day = "pondelok";break;
            case 2:$day = "utorok";break;
            case 3:$day = "streda";break;
            case 4:$day = "stvrtok";break;
            case 5:$day = "piatok";break;
            default: $day = "";
        }


        $parsed = $dom->getElementById($day);
        $tds = $parsed->getElementsByTagName('td');
        //$tr = $parsed->getElementsByTagName('tr');
        $h3s = $parsed->getElementsByTagName("h3");
        $b = $parsed->getElementsByTagName("b");
        $h2s = $parsed->getElementsByTagName("h2");
        // DEN
        foreach ($h2s as $h2) {
            for ($j=0;$j<3;$j++){
                //echo $h2->textContent;
                $dni_drag[] = $h2->textContent;
            }
            //echo $h3->textContent . "<br>";
            //$typ_jedla_drag[] = $h2->textContent;

        }


        $nazvy_drag[] = $b->item(0)->textContent;
        $cena_drag[] = "";
        foreach ($tds as $tag) {
            //CENA
            if ($tag->getAttribute('class') == 'text-right') {
                $trimmed = preg_replace('/^\W+|\W+$/', '', $tag->textContent);
                if ($trimmed == ""){
                    continue;
                }
                $cena_drag[] = $trimmed;
            }else{
                // NAZOV
                $trimmed = preg_replace('/^\W+|\W+$/', '', $tag->textContent);
                if ($trimmed == ""){
                    continue;
                }
                $nazvy_drag[] = $trimmed;
            }
        }
        // DRAG TYP JEDLA
        foreach ($h3s as $h3) {
            //echo $h3->textContent . "<br>";
            if ($h3->textContent === "HLAVNÉ JEDLO"){
                //echo "JEDLOOOOOOOOOOOOOO <br>";
                $typ_jedla_drag[] = $h3->textContent;
            }
            $typ_jedla_drag[] = $h3->textContent;

        }
    }
}

function insertToDatabaseDrag($db): bool{
    global $dni_drag, $nazvy_drag, $cena_drag, $typ_jedla_drag, $dom_drag;
    parseDrag($dom_drag);

    $sql = "INSERT INTO jedalne (nazov_jedalne,den, typ_jedla, nazov, cena) VALUES (?,?,?,?,?)";
    try{
        for ($i = 0; $i < count($nazvy_drag); $i++){
            $stmt = $db->prepare($sql);
            $stmt->execute(["Drag",$dni_drag[$i],$typ_jedla_drag[$i],$nazvy_drag[$i],$cena_drag[$i]]);
        }
        return true;
    }catch (Exception $e){
        return false;
    }
}
