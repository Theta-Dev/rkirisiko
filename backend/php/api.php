<?php
header('Content-type: text/json');

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

require 'vendor/autoload.php';

$url = 'https://www.rki.de/DE/Content/InfAZ/N/Neuartiges_Coronavirus/Risikogebiete_neu.html';

$header_map = array(
    '1. Folgende Staaten gelten aktuell als Virusvarianten-Gebiete:' => 'mutation',
    '2. Folgende Staaten/Regionen gelten aktuell Hochinzidenzgebiete:' => 'high_incidence',
    '3. Folgende Staaten/Regionen gelten aktuell als Risikogebiete:' => 'risk',
    '4. Gebiete, die zu einem beliebigen Zeitpunkt in den vergangenen 10 Tagen Risikogebiete waren, aber derzeit KEINE mehr sind:' => 'legacy'
);

function parse_date($date_str) {
    $date_map = array(
        'januar' => 1,
        'februar' => 2,
        'märz' => 3,
        'april' => 4,
        'mai' => 5,
        'juni' => 6,
        'juli' => 7,
        'august' => 8,
        'september' => 9,
        'oktober' => 10,
        'november' => 11,
        'dezember' => 12
    );

    preg_match('/^\d{1,2}/', $date_str, $m);
    $day = intval($m[0]);

    preg_match('/ [A-zÄ-ü]+ /', $date_str, $m);
    $month = $date_map[strtolower(trim($m[0]))] ?? 0;

    preg_match('/(?<=. )\d{4}/', $date_str, $m);
    $year = intval($m[0]);

    $date = new DateTime();
    $date->setDate($year, $month, $day);
    return $date->format('d.m.Y');
}


$file = file_get_contents($url);
$html = Pharse::str_get_dom($file);
$result = array();

// Go through all headers of the page
foreach($html('h2') as $header) {
    # Does the headline belong to a list that we want to parse?
    $headline = trim($header->getPlainTextUTF8());
    if(!array_key_exists($headline, $header_map)) continue;
    $key = $header_map[$headline];

    $list = $header;

    while(true) {
        # Find the list below the header
        do {
            $list = $list->getNextSibling();
        } while($list->tag != 'ul' && $list->tag != 'h2' && $list->tag != null);
        if($list->tag == 'h2' || $list->tag == null) break;

        # Go through all list items
        foreach($list->children as $item) {
            if($item->tag != 'li') continue;

            $inner_html = preg_replace('(^<li>|</li>$)', '', $item->html());
            $inner_html = str_replace('\n', '', $inner_html);
            $text = $item->getPlainTextUTF8();

            preg_match('/^([A-zÄ-ü.\'´`]|([ -][A-zÄ-ü.]))+/', $text, $m);
            $country = trim($m[0]);

            preg_match('/(?<=^'.preg_quote($country).'[ :–-]{2})[^(:;]+/', $text, $m);
            $country_detail = trim(iconv("UTF-8", "UTF-8//IGNORE", $m[0]));

            preg_match('/\d{1,2}. [A-zÄ-ü]+ \d{4}/', $text, $m);
            $date = parse_date($m[0]);

            $dataitem = array(
                #'country' => $country,
                'type' => $key,
                'detail' => $country_detail,
                'date' => $date,
                'html' => $inner_html
            );

            if(!array_key_exists($country, $result)) $result[$country] = array();
            array_push($result[$country], $dataitem);
        }
    }
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);
