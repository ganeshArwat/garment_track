<?php
 
include 'Array2XML.php';//include with path to your class file 'Array2XML'

$input = file_get_contents("php://input");

$input_array = json_decode($input,TRUE);

$data_array = $input_array['api_data'];
/* $new_array['api_data'] = $data_array;
$new_array['company_username'] = 'IMDTT';
$new_array['company_password'] = 'Test@1234';
echo '<pre>';print_r($new_array);
echo json_encode($new_array);exit; */
$root_node="TrackRequest";//root node of the xml tree like//root , data or anything you want to make super parent tag for the xml
$xml = Array2XML::createXML($root_node, $data_array);//
 
  $xml_STR = $xml->saveXML();// put string in xml_STR
  
  $resultTab = connectToEC3($xml_STR,$input_array);
  $xml_res = @simplexml_load_string($resultTab,"SimpleXMLElement", LIBXML_NOCDATA);
  //echo '<pre>';print_r($xml_res);exit;
   //$data = str_replace(array('<![CDATA[', ']]>', '\n'), array('', '', ''), $xml_res->asXML());
	$data = str_replace(array('<![CDATA[', '\n'), array('', ''), $xml_res->asXML());
   $xml_res_data = @simplexml_load_string($data);

  $final_res =  json_encode($xml_res_data);
  $final_res = str_replace('\n', "", $final_res);
  echo $final_res;
 
 function connectToEC3($xml_input,$data_array) {
	file_put_contents('tnt_track_input.txt',$xml_input);
 $url = "https://express.tnt.com/expressconnect/track.do?version=3";
 $password = isset($data_array['company_password']) ? $data_array['company_password'] : '';
$username = isset($data_array['company_username']) ? $data_array['company_username'] : '';
		
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode("$username:$password")));
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_POSTFIELDS, "xml_in=" . urlencode($xml_input));
 $result = curl_exec($ch);
 
 curl_close($ch);
 return $result;
 }
 
 
?>