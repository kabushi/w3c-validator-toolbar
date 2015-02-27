<?php
namespace W3cValidatorToolbar\Service;

use Zend\Http\Request;
use W3cValidatorToolbar\Service\W3cCssService\Response;
use W3cValidatorToolbar\Service\W3cCssService\Error;
use W3cValidatorToolbar\Service\W3cCssService\Warning;

class W3cCssService extends W3cService {
    
    public function validate($css_code) {
        $data = [
            'text'	        => $css_code,
            'output'        => 'soap12',
            'profile'       => 'css3',
            'usermedium'    => 'all',
            'type'          => 'none',
            'warning'       => 1,
            'vextwarning'   => '',
            'lang'          => 'fr'
        ];
        
        $request = new Request();
        $request->setUri('http://jigsaw.w3.org/css-validator/validator');
        $request->setMethod('POST');
        $request->getPost()->fromArray($data);
        
        $response = $this->client->send($request);
        
        $body = $this->_parseSOAP12Response($response->getBody());

        $ajax_r = [
            'status'   => (12 > 0 ? 'Invalid' : 'Valid'),
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
    
    public function _parseSOAP12Response($xml) {
        $doc = new \DOMDocument();
        if ($doc->loadXML($xml)) {
            $response = new Response();
        
            // Get the standard CDATA elements
            foreach (array('uri','checkedby','csslevel','date') as $var) {
                $element = $doc->getElementsByTagName($var);
                if ($element->length) {
                    $response->$var = $element->item(0)->nodeValue;
                }
            }
            // Handle the bool element validity
            $element = $doc->getElementsByTagName('validity');
            ;
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