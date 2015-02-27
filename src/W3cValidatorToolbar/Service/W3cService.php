<?php
namespace W3cValidatorToolbar\Service;


use Zend\Http\Client;

abstract class W3cService {
    
    protected $client;
    
    public function __construct() {
    
        $this->client = new Client();
        $this->client->setEncType(Client::ENC_FORMDATA);
    }

    /**
     * This function do the connection to the server to process the validation
     * 
     * @param String $fragment
     */
    abstract public function validate($fragment);
    
    /**
     * Parse an XML response from the validator
     *
     * This function parses a SOAP 1.2 response xml string from the validator.
     *
     * @param string $xml The raw soap12 XML response from the validator.
     *
     * @return mixed object Response | bool false
     */
    abstract function _parseSOAP12Response($xml);
}