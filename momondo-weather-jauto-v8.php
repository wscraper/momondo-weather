<?php 

set_time_limit(0);

#error_reporting(0);

$currentmonth = date('m');
require_once 'lib/central.config.php';
require_once 'lib/sdom.php';


$q1 = mysql_query("SELECT  `id`, `url`, `continent`, `month`  FROM `momondo_weather_v8` WHERE `month`!='$currentmonth' LIMIT 0, 30");


while($rows = mysql_fetch_assoc($q1))
{
	
$continent ='';

	
extract($rows);

echo PHP_EOL;
echo $id; echo PHP_EOL;echo PHP_EOL;

$contents = getPage($url);


$html = str_get_html($contents);

$temp = $html->find('div.info-weather li.temperature span.value', 0)->plaintext;
$temperature = str_replace('&#176;C', '',  $temp);
$day_dry = $html->find('div.info-weather li.sunnydays span.value', 0)->plaintext;
$avg_rainfall  = $html->find('div.info-weather li.rainfall span.value', 0)->plaintext;
$snow_days = $html->find('div.info-weather li.snowdays span.value', 0)->plaintext;


$weather_array = array(
'id' => trim($id),
'temp' => $temperature,
'day_dry' => $day_dry,
'avg_rainfall' => $avg_rainfall, 
'snow_days' => $snow_days,
'url' => $url,
'continent' => $continent,
'month' => trim($currentmonth),
'last_update' => date('Y-m-d'),
);

saveData($weather_array);
//$data[] = $weather_array;



} #EO While Loop

/*
echo '<pre>';
print_r($data);
echo '</pre>';
*/

function saveData($datas)
{
extract($datas);

$temp  = addslashes(trim($temp));						              
$day_dry = addslashes(trim($day_dry));				                    
$avg_rainfall = addslashes(trim($avg_rainfall));		              
$snow_days = addslashes(trim($snow_days));
$url = addslashes($url);
$continent = addslashes($continent);
$month = addslashes($month);
$last_update = addslashes($last_update);


$idx = mysql_query("SELECT `id` FROM `momondo_weather_v8` WHERE `id`='$id'");

$count = mysql_num_rows($idx);

mysql_query( "SET NAMES 'utf8'");

if($count)
{
   
	echo $q2 = "UPDATE `momondo_weather_v8` SET  
 				 `temp`='$temp',
 				 `day_dry`='$day_dry',
 				 `avg_rainfall`='$avg_rainfall',
 				 `snow_days`='$snow_days',
 				 `month`='$month',
 				 `last_update`='$last_update'
 				 WHERE `id`='$id'"; echo '<br>';echo '<br>'; echo PHP_EOL;
	mysql_query($q2) or die(mysql_error($q2));
}



}


function getPage($page, $redirect = 0, $cookie_file = '')
{         
   $ch = curl_init();
   $referer = 'http://www.momondo.com';
   $headers = array("Expect:");
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
   
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
   curl_setopt($ch, CURLOPT_HEADER, 0);
   
   if($redirect)
   {
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    
   }
   
   curl_setopt($ch, CURLOPT_URL, $page);
   curl_setopt($ch, CURLOPT_REFERER, $referer);
   
   if($cookie_file != '') {
     curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file);
     curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
   }
 
   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0');
   
   $return = curl_exec($ch);  
         
   curl_close($ch);
      
   return $return;
   
}//EO Fn	
?>