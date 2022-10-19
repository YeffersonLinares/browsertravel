<?php

$start = $_REQUEST['start'];
$end = $_REQUEST['end'];

// echo $start . ' => ' . $end;

echo requestData($start, $end);


function requestData($start, $end)
{

    $username = 'ileven_linares';
    $password = '3W4Yl1V1zp';

    $context = stream_context_create(
        array(
            'http' => array(
                'header' => 'Authorization: Basic ' . base64_encode("$username:$password")
            )
        )
    );

    $response = [];
    //   $url = "https://api.meteomatics.com/2022-10-20T06:00:00ZP2D:PT1H/t_2m:C,relative_humidity_2m:p/47.423336,9.377225/csv";
    $url_start = '';
    $url_end = '';

    $ahora = time();
    $unDiaEnSegundos = 24 * 60 * 60;
    $manana = $ahora + $unDiaEnSegundos;
    $mananaLegible = date("Y-m-d H:i:s", $manana);
    # ahoraLegible únicamente es para demostrar
    $ahoraLegible = date("Y-m-d H:i:s", $ahora);

    if (!empty($start)) {
        $start = new DateTime($start);
        $url_start = $start->format(DateTime::ISO8601);
    } else {
        $start = new DateTime($ahoraLegible);
        $url_start = $start->format(DateTime::ISO8601);
    }
    if (!empty($end)) {
        $end = new DateTime($end);
        $url_end = $end->format(DateTime::ISO8601);
    } else {
        $end = new DateTime($mananaLegible);
        $url_end = $end->format(DateTime::ISO8601);
    }

    $url_miami = "https://api.meteomatics.com/{$url_start}--{$url_end}:PT12H/t_2m:C,relative_humidity_2m:p/25.7741728,-80.19362/csv";
    $url_orlando = "https://api.meteomatics.com/{$url_start}--{$url_end}:PT12H/t_2m:C,relative_humidity_2m:p/28.542111,-80.19362/csv";
    $url_new_york = "https://api.meteomatics.com/{$url_start}--{$url_end}:PT12H/t_2m:C,relative_humidity_2m:p/40.7127281,-74.0060152/csv";
    // $url = "https://api.meteomatics.com/2022-10-19T00:00:00+0000--2022-12-20T00:00:00+0000:PT12H/t_2m:C,relative_humidity_2m:p/4.3556,74.0451/csv";
    $response = [
        'miami' => file_get_contents($url_miami, false, $context),
        'orlando' => file_get_contents($url_orlando, false, $context),
        'new_york' => file_get_contents($url_new_york, false, $context),
    ];
    return json_encode($response);

    // return $response;
}
