<?php

require_once 'Configs/Config.php';
require_once 'Lib/Formula.php';

$start_time = microtime(true);

//Retrieve data from URL
$dataset = Formula::getDataSet(Config::URL);

if (json_decode($dataset, TRUE)) {
    $decoded_set = json_decode($dataset, TRUE);

    //Calculate
    $calc = Formula::Calculate($decoded_set);
    $tat = microtime(true)-$start_time;
    
    //TAT is useful for redesign of slow Formula
    $tat = sprintf("%.2f",(microtime(true) - $start_time));

    //Display the response in array format 
    print_r($calc);
    
    //Or display the response in json format 
    //echo json_encode($calc);
} else {
    
    //return an invalid response message
     $resp = array(
        "message" => Config::INVALID_RESPONSE_FORMAT,
        "suggestion"=>array(
            "Check the url by doing a curl -i[k] [URL]",
            "Check and Validate the dataset being returned")
    );
    
    die(json_encode($resp));
}
