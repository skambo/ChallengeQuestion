<?php

$url = "https://raw.githubusercontent.com/onaio/ona-tech/master/data/water_points.json";
//Define the header request
define("USAGE", "CHALLENGE");
//Rank percentages
define("RATE", 100);
//Format final value
define("FORMAT", "%.2f");
//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);

// Will dump a beauty json :3
$dataset = json_decode($result, true);
$calc = Calculate($dataset);

    //Display the response in table format
	print "<strong>Functional Water Points</strong>: ".$calc['functional_water_points']."<br>";
	
	echo  "<table><th>Number of Water Points<br></th>";
	foreach ($calc['number_of_water_points'] as $waterpts=>$item){
    echo "<tr><td>".$waterpts."</td><td>".$item."</td></tr>";
}
echo "</table>";
    echo "<table><th>Community Name Rating</th>";
	foreach ($calc['community_ranking'] as $ranking=>$item){
    echo "<tr><td>".$ranking."</td><td>".$item."</td></tr>";
}
echo "</table>";

function Calculate($dataset) {
        $counts = array();
        $community_array = array(); //the number of water points in the community
        $community_arrayx = array(); //the non-functional water points
        $ranking_array = array(); //rank by percentage
        $counter = 0;
        
        foreach ($dataset as $data => $value) {
            if (isset($value['water_functioning'])) {
                $stat = $value['water_functioning'];
                $community = $value['communities_villages'];
                if (!in_array($community, $community_arrayx)) {
                    array_push($community_arrayx, $community);
                }                
                if (strcasecmp($stat, "yes") == 0) {
                    ++$counter;
                }
            }
        }

        $cc_arr=array();
        
        for ($y = 0; $y < count($community_arrayx); ++$y) {
            $comm = $community_arrayx[$y];
            $community_array[$comm] 
                    = getWaterPointsToTal($dataset, $comm);
            
            $cc_arr[$comm] 
                    = getBrokenWaterPointsToTal($dataset, $comm);
            $ranking_array = $cc_arr;
        }
        
                
        $counts['functional_water_points'] = $counter;
        $counts['number_of_water_points'] = $community_array;
        $counts['community_ranking'] = getRanking($ranking_array);

        return $counts;
    }
	
	function getWaterPointsToTal($dataset, $community,
            $functioning = 'yes') {
        $counter = 0;
        foreach ($dataset as $data => $value) {
            if (isset($value['communities_villages'])) {
                $community_cmp = $value['communities_villages'];
                if (strcasecmp($community, $community_cmp) == 0) {
                    if ($functioning == NULL || empty($functioning)) {
                        ++$counter;
                    } else {
                        $stat = $value['water_functioning'];
                        if (strcasecmp($functioning, $stat) == 0) {
                            ++$counter;
                        }
                    }
                }
            }
        }
        return $counter;
    }
	
	function getBrokenWaterPointsToTal($dataset, $community) {
        $counter = 0;
        foreach ($dataset as $data => $value) {
            if (isset($value['communities_villages'])) {
                $community_cmp = $value['communities_villages'];
                if (strcasecmp($community, $community_cmp) == 0) {
                    if(isset($value['water_point_condition'])){
                        $stat = $value['water_point_condition'];
                        if (strcasecmp($stat, "broken") == 0) {
                            ++$counter;
                        }
                    }
                }
            }
        }
        return $counter;
    }
	
	function getRanking($data) {
        $values = array();
        $total=0;
        foreach ($data as $community=>$broken_water_points){
            $total=$total+$broken_water_points;
        }
        //Calculate the percentages
        $perc = array();
        foreach ($data as $community=>$broken_water_points){
            $p = ($broken_water_points*RATE)/$total;
            $perc[$community]=  sprintf(FORMAT, $p);
        }
        //Sort array based on percentages
        array_multisort($perc);
        //Return the final values.
        return $perc;
    }
?>

