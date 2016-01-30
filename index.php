<?php

$url = "https://raw.githubusercontent.com/onaio/ona-tech/master/data/water_points.json";

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
$result = json_decode($result, true);
echo'<table>';
foreach ($result as $results){

        echo'<tr>'; 
        echo'<td>'. $results['water_pay']."</td>";
        echo'<td>'. $results['respondent'].'</td>';
        echo'<td>'. $results['research_asst_name'].'</td>';
		 echo'<td>'. $results['water_used_season']."</td>";
        echo'<td>'. $results['_bamboo_dataset_id'].'</td>';
        echo'<td>'. $results['_deleted_at'].'</td>';
		 echo'<td>'. $results['water_point_condition']."</td>";
        echo'<td>'. $results['_xform_id_string'].'</td>';
        echo'<td>'. $results['other_point_1km'].'</td>';
        echo'<tr>';
    }
echo'</table>';

?>
Attachments area
Preview attachment Software Engineer Challenge Question
