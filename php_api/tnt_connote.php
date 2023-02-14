<?php
 
include 'Array2XML.php';//include with path to your class file 'Array2XML'

$input = file_get_contents("php://input");

$data_array = json_decode($input,TRUE);

 $resultTab = $data_array['access_code'];
 // Get documentation (connote, label, manifest and invoice)
$connoteTab = connectToEC3("GET_CONNOTE:" . preg_replace("/[^0-9]/", "", $resultTab));
//$labelTab = connectToEC3("GET_LABEL:" . preg_replace("/[^0-9]/", "", $resultTab));
//$manifestTab = connectToEC3("GET_MANIFEST:" . preg_replace("/[^0-9]/", "", $resultTab));
//$invoiceTab = connectToEC3("GET_INVOICE:" . preg_replace("/[^0-9]/", "", $resultTab));
// Show documentation
$html_res =  showXMLasHTML($connoteTab);
//$minify_res = str_replace(array("\r","\n"),"",$html_res);
echo $html_res;
/*
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