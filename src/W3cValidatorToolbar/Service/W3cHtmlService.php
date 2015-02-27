<?php
namespace W3cValidatorToolbar\Service;


use Zend\Http\Request;
use W3cValidatorToolbar\Service\W3cHtmlService\Response;
use W3cValidatorToolbar\Service\W3cHtmlService\Error;
use W3cValidatorToolbar\Service\W3cHtmlService\Warning;
use Zend\Http\Client;

class W3cHtmlService extends W3cService {
    protected $client;
    public function __construct() {
        $this->client = new Client();
        $this->client->setEncType(Client::ENC_FORMDATA);
    }
    
    public function validate($fragment) {
        $data = [
            'fragment' => $fragment,
            'output' => 'soap12',
        ];
        
        $request = new Request();
        $request->setUri('http://validator.w3.org/check');
        $request->setMethod('POST');
        $request->getPost()->fromArray($data);
        
        $response = $this->client->send($request);
        $body = $this->_parseSOAP12Response($response->getBody());
        
        $ajax_r = [
            'status'   => (sizeof($body->errors) > 0 ? 'Invalid' : 'Valid'),
            'errors'   => [
                'num'  => sizeof($body->errors),
                'list' => $body->errors
            ],
            'warnings' => [
                'num'  => sizeof($body->warnings),
                'list' => $body->warnings
            ]
        ];
        
        return $ajax_r;
    }
    
    /**
     * Parse an XML response from the validator
     *
     * This function parses a SOAP 1.2 response xml string from the validator.
     *
     * @param string $xml The raw soap12 XML response from the validator.
     *
     * @return mixed object Response | bool false
     */
    function _parseSOAP12Response($xml) {
        $doc = new \DOMDocument();
        if ($doc->loadXML($xml)) {
            $response = new Response();
    
            // Get the standard CDATA elements
            foreach (array('uri','checkedby','doctype','charset') as $var) {
                $element = $doc->getElementsByTagName($var);
                if ($element->length) {
                    $response->$var = $element->item(0)->nodeValue;
                }
            }
            // Handle the bool element validity
            $element = $doc->getElementsByTagName('validity');
            if ($element->length &&
                $element->item(0)->nodeValue == 'true') {
                    $response->validity = true;
                } else {
                    $response->validity = false;
                }
                if (!$response->validity) {
                    $errors = $doc->getElementsByTagName('error');
                    foreach ($errors as $error) {
                        $error = new Error($error);
                        if($error !== null) {
                            $response->errors[] = $error;
                        }
                    }
                }
                $warnings = $doc->getElementsByTagName('warning');
                foreach ($warnings as $warning) {
                    $warning = new Warning($warning);
                    if($warning->line !== null) {
                        $response->warnings[] = $warning;
                    }
                }
                return $response;
        } else {
            // Could not load the XML.
            return false;
        }
    }
}