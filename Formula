<?php
//Define the header request
define("USAGE", "CHALLENGE");

//Rank the percentage
define("RATE", 100);

//The format in which to display the final value
define("FORMAT", "%.2f");

class Formula {

    /**
     * Calculates the functional water points,water points per 
     * community and rank for each community by the percentage
     * of broken water points.
     * 
     * @param type $dataset
     */
    public static function Calculate($dataset) {
        $counts = array();
        $community_array = array(); //water points per community
        $community_arrayx = array(); //non-functional water points
        $ranking_array = array(); //ranking  by percentage
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
                    = Formula::getWaterPointsToTal($dataset, $comm);
            
            $cc_arr[$comm] 
                    = Formula::getBrokenWaterPointsToTal($dataset, $comm);
            $ranking_array = $cc_arr;
        }
        
                
        $counts['functional_water_points'] = $counter;
        $counts['number_of_water_points'] = json_encode($community_array);
        $counts['community_ranking'] = Formula::getRanking($ranking_array);

        return $counts;
    }
    
    /* Count the number of water points per community.
     * 
     * @param type $dataset
     * @param type $community
     * @param type $functioning - optional
     */
    private static function getWaterPointsToTal($dataset, $community,
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

    /**
     * Return water points that are broken.
     * 
     * @param type $dataset
     * @param type $community
     * @return int
     */
    private static function getBrokenWaterPointsToTal($dataset, $community) {
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
    
    /**
     * Calculate the ranking by community in Descending Order.
     * 
     * @param type $dataset
     * @param type $community
     */
    private static function getRanking($data) {
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
        //Sort the associative array based on calculated 
        //percentages
        array_multisort($perc);
        //Return the final values.
        return json_encode($perc);
    }

    /**
     * Function retrieve the data from the given URL.
     *  
     * @param type $url
     * @return string
     */
    public static function getDataSet($url) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
			CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => USAGE));
        $resp = curl_exec($ch);
        curl_close($ch);
        return $resp;
    }
}
