<?php

require_once 'Config.php';
require_once 'Formula.php';

$start_time = microtime(true);

//Retrieve data from URL
$dataset = Formula::getDataSet(Config::URL);

if (json_decode($dataset, TRUE)) {
    $decoded_set = json_decode($dataset, TRUE);

    //Calculate
    $calc = Formula::Calculate($decoded_set);
    $tat = microtime(true)-$start_time;

    //Display the response in array format 
    print_r($calc);
    
    //Or display the response in json format 
    //echo json_encode($calc);
} else {
    
    //return an invalid response message
     $resp = array(
        "message" => Config::Incorrect_Response,
        "suggestion"=>array(
            "Check the url by doing a curl -i[k] [URL]",
            "Check and Validate the dataset being returned")
    );
    
    die(json_encode($resp));
}
