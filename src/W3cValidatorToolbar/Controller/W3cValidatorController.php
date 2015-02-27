<?php
namespace W3cValidatorToolbar\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\JsonModel;

class W3cValidatorController extends AbstractActionController {
    
    protected $serviceLocator;
    
    public function __construct(ServiceLocatorInterface $serviceLocator) {
	    $this->serviceLocator     = $serviceLocator;
    }
    
    public function indexAction() {
        return new ViewModel();
    }
    
    public function ajaxAction() {
        $retour_r = [];
        switch($this->getRequest()->getPost("type")) {
            case 'html':
                $htmlservice = $this->serviceLocator->get("W3cHtml");
                $retour_r = $htmlservice->validate($this->getRequest()->getPost("fragment"));
                break;
            case 'css':
                $config = $this->serviceLocator->get("config");
                $text = $this->getRequest()->getPost("text");
                $files = $this->getRequest()->getPost("links");
                

                
                foreach($files as $file) {
                    // avoid some files
                    if(!in_array(basename($file), $config["w3c-validator-module"]["css"]["exclude_files"])) {
                        $src = @file_get_contents($file);
                        
                        if($src === false) {
                            $text .= "\n\n/** --------\n * Error while grabbing " . $file . "\n --------*/\n\n";
                        } else {
                            $text .= "\n\n/** --------\n * " . $file . "\n --------*/\n\n" . $src;
                        }
                    }
                }
                $cssService = $this->serviceLocator->get("W3cCss");
                $retour_r = $cssService->validate($text);
                $retour_r["src"] = $text;
                
            default:
                break;
        }
        return new JsonModel($retour_r);
    }
    
    public function ressourceAction() {
        
        
        $filename = dirname(__FILE__) . "/../../../ressources/" . $slug= $this->params("url");
        
        if(!file_exists($filename)) {
            $this->getResponse()->setStatusCode(404);
            return;
        } else {
            
            
            $finfo = new \finfo(FILEINFO_MIME);
            $type = $finfo->file($filename);
            
            $this->getResponse()->getHeaders()->addHeaderLine('Content-Type', $type);
            $content = file_get_contents($filename);
            $view = new ViewModel([
                'content' => $content
            ]);
            $view->setTerminal(true);
            return $view;
        }
    }
}

?>