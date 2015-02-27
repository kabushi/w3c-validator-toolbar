<?php

namespace W3cValidatorToolbar\Service\W3cHtmlService;

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
     * column corresponding to the message
     * 
     * Within the source code of the validated document, refers to the column within
     * the line for the message.
     * @var int
     */
    public $col;
    
    /**
     * The actual message
     * @var string
     */
    public $message;
    
    /**
     * Unique ID for this message
     * 
     * not implemented yet. should be the number of the error, as addressed
     * internally by the validator
     * @var int
     */
    public $messageid;
    
    /**
     * Explanation for this message.
     * 
     * HTML snippet which describes the message, usually with information on
     * how to correct the problem.
     * @var string
     */
    public $explanation;
    
    /**
     * Source which caused the message.
     * 
     * the snippet of HTML code which invoked the message to give the 
     * context of the e
     * @var string
     */
    public $source;
    
    /**
     * Constructor for a response message
     *
     * @param object $node A dom document node.
     */
    function __construct($node = null)
    {
        if (isset($node)) {
            $el = $node->getElementsByTagName("messageid");
            if($el->length) {
                // do not display the W28 Warning
                if($el->item(0)->nodeValue == "W28") {
                    return null;
                }
            }
            
            foreach (get_class_vars('W3cValidatorToolbar\Service\W3cHtmlService\Message') as $var => $val) {
                $element = $node->getElementsByTagName($var);
                if ($element->length) {
                    $this->$var = $element->item(0)->nodeValue;
                }
            }
        }
    }
}

?>