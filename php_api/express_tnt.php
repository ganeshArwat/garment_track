<?php
 
 
include 'Array2XML.php';//include with path to your class file 'Array2XML'

$input = file_get_contents("php://input");

file_put_contents('tnt_input',$input);
$data_array = json_decode($input,TRUE);

$package_array = $data_array['CONSIGNMENTBATCH']['CONSIGNMENT']['DETAILS']['PACKAGES'];
unset($data_array['CONSIGNMENTBATCH']['CONSIGNMENT']['DETAILS']['PACKAGES']);
if (isset($package_array) && is_array($package_array) && count($package_array) > 0) {
            foreach ($package_array as $rkey => $rvalue) {
				$new_package[] = $rvalue['PACKAGE'];
			}
}
$data_array['CONSIGNMENTBATCH']['CONSIGNMENT']['DETAILS']['PACKAGE'] = isset($new_package) ? $new_package : array();


$root_node="ESHIPPER";//root node of the xml tree like//root , data or anything you want to make super parent tag for the xml
$xml = Array2XML::createXML($root_node, $data_array);//
  
  $xml_STR = $xml->saveXML();// put string in xml_STR
  $xml->save('array_to_xml_convert.xml'); // save data in array_to_xml_convert.xml file
  
 
 $xml_input = file_get_contents('array_to_xml_convert.xml');
 $resultTab = connectToEC3($xml_STR);
 //echo "RES=".$resultTab;
 $resultTab2 = connectToEC3("GET_RESULT:" . preg_replace("/[^0-9]/", "", $resultTab));
 //echo "resultTab2=".$resultTab2;
 $xml_res = @simplexml_load_string($resultTab2);

 
 $json_response = json_encode($xml_res);
 
 $json_response_arr = json_decode($json_response,TRUE);
 $json_response_arr['access_code'] = preg_replace("/[^0-9]/", "", $resultTab);
 echo json_encode($json_response_arr);
 
 // Get documentation (connote, label, manifest and invoice)
$connoteTab = connectToEC3("GET_CONNOTE:" . preg_replace("/[^0-9]/", "", $resultTab));
$labelTab = connectToEC3("GET_LABEL:" . preg_replace("/[^0-9]/", "", $resultTab));
$manifestTab = connectToEC3("GET_MANIFEST:" . preg_replace("/[^0-9]/", "", $resultTab));
$invoiceTab = connectToEC3("GET_INVOICE:" . preg_replace("/[^0-9]/", "", $resultTab));
// Show documentation
/*echo "Connote:<br>";
print showXMLasHTML($connoteTab);
echo "Label:<br>";
print showXMLasHTML($labelTab);
echo "Manifest:<br>";
print showXMLasHTML($manifestTab);
echo "Invoice:<br>";
print showXMLasHTML($invoiceTab);*/

 //echo $result_json;
 
 function connectToEC3($xml_input) {
 $url = "https://express.tnt.com/expressconnect/shipping/ship";
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_POSTFIELDS, "xml_in=" . urlencode($xml_input));
 $result = curl_exec($ch);
 curl_close($ch);
 return $result;
 }
 
 
 // Then a function to visualize the returned xml in html page
function showXMLasHTML($xml_received){
 //get stylesheet location from xml
 $dom = new DOMDocument();
 $dom->loadXml($xml_received);
 $xpath = new DOMXpath($dom);
 $styleLocation = $xpath->evaluate('string(//processing-instruction()[name() = "xml-stylesheet"])');
 $last = explode("\"", $styleLocation, 3);
 $xslLocation = $last[1];
 $xslt = new xsltProcessor;
 $xslt->importStyleSheet(DomDocument::load($xslLocation));
 $html = $xslt->transformToXML(DomDocument::loadXML($xml_received));
 return $html;
 }
 
?>