<?php
/**
 * myControllerRequest
 */
require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';
/**
 * Encapsulates the interaction of MODx manager with an HTTP request.
 *
 * {@inheritdoc}
 *
 * @extends modRequest
 */
class myControllerRequest extends modRequest {
    public $cmpController = null;
    public $actionVar = 'action';
    public $defaultAction = 'home';

    // function __construct(cmpController &$cmpController) {
    function __construct(myController &$cmpController) {
        parent :: __construct($cmpController->modx);
        $this->cmpController =& $cmpController; 
    }

    /**
     * Extends modRequest::handleRequest and loads the proper error handler and
     * actionVar value.
     *
     * {@inheritdoc}
     */
    public function handleRequest() {
        $this->loadErrorHandler();

        /* save page to manager object. allow custom actionVar choice for extending classes. */
        $this->action = isset($_REQUEST[$this->actionVar]) ? $_REQUEST[$this->actionVar] : $this->defaultAction;

        return $this->_respond();
    }

    /**
     * Prepares the MODx response to a mgr request that is being handled.
     *
     * @access public
     * @return boolean True if the response is properly prepared.
     */
    private function _respond() {
        $modx =& $this->modx;
        $cmpController =& $this->cmpController;
        $viewHeader = '';//include $this->cmpController->config['corePath'].'controllers/mgr/header.php';
        $viewOutput = '';
        // load the DB package that is used for this component
        $modx->setLogTarget('HTML');
        // This will add the package to xPDO, and allow you to use all of xPDO's functions with your model. 
        $modx->addPackage($this->cmpController->config['packageName'], $this->cmpController->config['modelPath']);
        
        // include some custom classes to help handle the forms
        require_once $this->cmpController->config['modelPath'].'datatosmarty.class.php';
        require_once $this->cmpController->config['modelPath'].'formvalidate.class.php';
        
        // get the a - modxAction id
        if ( !isset($a) ) {
            if ( isset($_REQUEST['a']) ) {
                $a = $_REQUEST['a'];
            }
        }
        // assign the package name to a var
        $package_name = $this->cmpController->config['packageName'];
        // if form has been submitted then process it
        if ( isset($_POST) ) {
            $folder = isset($_REQUEST['pfolder']) ? str_replace(array('/','\\','..'),'',$_REQUEST['pfolder']) : '';
            $f = $this->cmpController->config['corePath'].'processors/mgr/'.$folder.'/'.$this->action.'.php';
            // echo 'File: '.$f;
            if (file_exists($f)) {
                //$viewHeader .= 'Process me';
                $viewOutput .= include $f;
                if ( !empty($viewOutput)){
                    $viewOutput = '<div id="jgMessage">'.$viewOutput.'</div>';
                }
            }
        } 
        
        
        // call on a template page
        $f = $this->cmpController->config['corePath'].'controllers/mgr/'.$this->action.'.php';
        if (file_exists($f)) {
            $viewOutput .= include $f;
        } else {
            $viewOutput .= 'Action not found: '.$f;
        }
        // put html into wrapper
        $modx->smarty->assign('html',$viewOutput);
        return $modx->smarty->fetch( MODX_CORE_PATH.'components/newsmanager/templates/wrapper.tpl' );
        
        //return $viewHeader.$viewOutput;
    }
}