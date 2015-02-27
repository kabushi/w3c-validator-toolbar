<?php

namespace W3cValidatorToolbar\Service\W3cCssService;

/**
 * This file provides a base class for messages from a W3C validator.
 */

/**
 * The message class holds a response from the W3 validator.
 */ 
class Message
{
    /**
     * line corresponding to the message
     * 
     * Within the source code of the validated document, refers to the line which
     * caused this message.
     * @var int
     */
    public $line;
    
    
    /**
     * The actual message
     * @var string
     */
    public $message;
    

    /**
     * The actual level message
     * @var string
     */
    public $level;
    
    
    /**
     * Explanation for this message.
     * 
     * Type of message
     * @var string
     */
    public $type;
    
    
    /**
     * Constructor for a response message
     *
     * @param object $node A dom document node.
     */
    function __construct($node = null)
    {
        if (isset($node)) {
            
            foreach (get_class_vars('W3cValidatorToolbar\Service\W3cCssService\Message') as $var => $val) {
                $element = $node->getElementsByTagName($var);
                if ($element->length) {
                    $this->$var = $element->item(0)->nodeValue;
                }
            }
        }
    }
}

?>