<?php
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
$header_data = getallheaders();
class DebugSoapClient extends SoapClient
{
    public $sendRequest = true;
    public $printRequest = true;
    public $formatXML = true;


    public function __doRequest($request, $location, $action, $version, $one_way=0)
    {
		
		file_put_contents('xml_request.txt',$request);
        if ($this->printRequest) {
            if (!$this->formatXML) {
                $out = $request;
				
            } else {
                $doc = new DOMDocument;
                $doc->preserveWhiteSpace = false;
                $doc->loadxml($request);
				$doc->formatOutput = true;
                $out = $doc->savexml();
            }
        }


        if ($this->sendRequest) {
            return parent::__doRequest($request, $location, $action, $version, $one_way);
        } else {
            return '';
        }
    }
}
// if (isset($header_data['api-key']) && $header_data['api-key'] == 'BMyORfyNPwrXADp') {
    #echo "Start  of Soap 1.2 version (ws_http_Binding)  setting";
    $soap = new DebugSoapClient(
        //'http://netconnect.bluedart.com/Ver1.9/Demo/ShippingAPI/WayBill/WayBillGeneration.svc?wsdl',
		  'https://netconnect.bluedart.com/Ver1.10/ShippingAPI/WayBill/WayBillGeneration.svc?wsdl',
        array(
    'trace' 							=> 1,
    'style'								=> SOAP_DOCUMENT,
    'use'									=> SOAP_LITERAL,
    'soap_version' 				=> SOAP_1_2
    )
    );
    
    //$soap->__setLocation("http://netconnect.bluedart.com/Ver1.9/Demo/ShippingAPI/WayBill/WayBillGeneration.svc");
	$soap->__setLocation("https://netconnect.bluedart.com/Ver1.10/ShippingAPI/WayBill/WayBillGeneration.svc");
    
    $soap->sendRequest = true;
    $soap->printRequest = false;
    $soap->formatXML = true;
    
    $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing', 'Action', 'http://tempuri.org/IWayBillGeneration/GenerateWayBill', true);
    $soap->__setSoapHeaders($actionHeader);
    #echo "end of Soap 1.2 version (WSHttpBinding)  setting";

    $input = file_get_contents("php://input");
	file_put_contents('bluedart_header.txt', json_encode($header_data));
    file_put_contents('bluedart_input.txt', $input);
    $input_data = json_decode($input, true);
    $params = $input_data['GenerateWayBill'];
    if (isset($params) && is_array($params) && count($params) > 0) {
        // Here I call my external function
        $result = $soap->__soapCall('GenerateWayBill', array($params));
		file_put_contents('xml_response_pure.txt', $result->GenerateWayBillResult->AWBPrintContent);
		
		
		if($result->GenerateWayBillResult->AWBPrintContent !=''){
			$awb_no = $result->GenerateWayBillResult->AWBNo;
			
			$pdf_filename = 'bluedart_pdf/waybill_'.$awb_no.'.pdf';
			file_put_contents($pdf_filename,$result->GenerateWayBillResult->AWBPrintContent);
			$b64Doc = chunk_split(base64_encode(file_get_contents($pdf_filename)));
	
			$result->GenerateWayBillResult->AWBPrintContent = $b64Doc;
			unlink($pdf_filename);
		}
		
		file_put_contents('xml_response.txt', serialize($result));
        //echo "Generated Waybill number " + $result->GenerateWayBillResult->AWBNo;
        #echo $result->GenerateWayBillResult->Status->WayBillGenerationStatus->StatusInformation

        $json_response = json_encode($result);
		
        file_put_contents('bluedart_response.txt', $json_response);
        echo $json_response;
    } else {
        $response = array(
            'status' => 'failure',
            'reason' => 'No data found'
        );
        echo json_encode($response);
        http_response_code(403);
    }
/* } else {
    $response = array(
        'status' => 'failure',
        'reason' => 'Invalid PHP Bluedart API key'
    );
    echo json_encode($response);
    http_response_code(403);
} */
