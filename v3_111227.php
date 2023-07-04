<?php
define("URL_V3","https://site12.webte.fei.stuba.sk/ide/app/kUyrS5IQkk.html" );

$curl = curl_init(URL_V3);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$page = curl_exec($curl);

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($page);
libxml_use_internal_errors(false);

// POCET P TAGOV
$p = $dom->getElementsByTagName("p");;

$countP = $p->length;

//VYSKYT PODRETAZCA IMG
$pos = strpos($page, "<img");

preg_match_all('/[A-Z]+/', $page, $matches);
$pocetPismien = count($matches[0]);

//POCET CISEL
preg_match_all('/\d+\.\d+|\d+/', $page, $matches2);
$pocetCisel = count($matches2[0]);


$xpath = new DOMXPath($dom);
$comments = $xpath->query('//comment()');
$html_comment_count = 0;
foreach ($comments as $comment) {
    $comment_text = $comment->nodeValue;
    // Filter out comments that contain URLs
    if (!preg_match('/http(s)?:\/\//i', $comment_text)) {
        $html_comment_count++;
    }
}
// Count JS comments
$js_pattern = '/\/\*[\s\S]*?\*\/|\/\/.*/';
preg_match_all($js_pattern, $page, $js_matches);
$js_comment_count = 0;
foreach ($js_matches[0] as $match) {
    $match_text = trim($match);
    // Filter out matches that contain URLs
    if (!preg_match('/http(s)?:\/\//i', $match_text)) {
        $js_comment_count++;
    }
}
// Count CSS comments
$css_pattern = '/\/\*[\s\S]*?\*\//';
preg_match_all($css_pattern, $page, $css_matches);
$css_comment_count = 0;
foreach ($css_matches[0] as $match) {
    $match_text = trim($match);
    // Filter out matches that contain URLs
    if (!preg_match('/http(s)?:\/\//i', $match_text)) {
        $css_comment_count++;
    }
}


$countAllComments = $html_comment_count + $js_comment_count + $css_comment_count;

$arr = array($countP, $pos, $pocetPismien, $countAllComments, $pocetCisel);

foreach ($arr as $value) {echo($value.' ');}



?>
