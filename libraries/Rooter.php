<?php
class Rooter
{
    private $_url = null;
    private $_controller = null;

    private $_controllerPath = 'controllers/';
    private $_modelPath = 'models/';
    private $_errorFile = 'errors.php';
    private $_defaultFile = 'index.php';


    public function __construct()
    {
        $this->_url = $this->_checkDatabase();
        if(!$this->_url) $this->_getUrl();

        if (empty($this->_url[0])) {
            $this->_loadDefaultController();
            return false;
        }

        $this->_loadExistingController();
        $this->_callControllerMethod();
    }

    public function setControllerPath($path)
    {
        $this->_controllerPath = trim($path, '/') . '/';
    }

    public function setModelPath($path)
    {
        $this->_modelPath = trim($path, '/') . '/';
    }


    public function setErrorFile($path)
    {
        $this->_errorFile = trim($path, '/');
    }


    public function setDefaultFile($path)
    {
        $this->_defaultFile = trim($path, '/');
    }

    private function _getUrl()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = filter_var($url,FILTER_SANITIZE_URL);
        $this->_url = explode('/', $url);

    }

    private function _loadDefaultController()
    {
        require_once $this->_controllerPath . $this->_defaultFile;
        $this->_controller = new Index();
        $this->_controller->loadModel('index', $this->_modelPath);
        $this->_controller->main();
    }

    private function _loadExistingController()
    {
        $file = $this->_controllerPath . $this->_url[0] . '.php';

        if (file_exists($file)) {
            require_once $file;
            $this->_controller = new $this->_url[0];
            $this->_controller->loadModel($this->_url[0], $this->_modelPath);
        } else {
            require_once $this->_controllerPath . 'index.php';
            if(method_exists(new Index(),$this->_url[0]))
            {
                $this->_controller = new Index();
                $this->_controller->loadModel('index', $this->_modelPath);
                array_unshift($this->_url,$this->_url[0]);
            } else {
                $this->_error();
                return false;
            }
        }
    }

    private function _callControllerMethod()
    {
        $length = count($this->_url);

        if ($length > 1) {
            if (!method_exists($this->_controller, $this->_url[1])) {
                $this->_error();
            }
        }

        switch ($length) {
            case 5:
                //Controller->Method(Param1, Param2, Param3)
                $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3], $this->_url[4]);
                break;

            case 4:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3]);
                break;

            case 3:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}($this->_url[2]);
                break;

            case 2:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}();
                break;

            default:
                $this->_controller->main();
                break;
        }
    }

    /**
    * Hata controller'ı
    *
    * @return boolean
    */
    private function _error() {
        require_once $this->_controllerPath . $this->_errorFile;
        $this->_controller = new Errors();
        $this->_controller->loadModel('errors', $this->_modelPath);
        $this->_controller->error_404();
        return true;
    }

    private function  _checkDatabase()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        if(!$url) return false;

        $url = filter_var($url,FILTER_SANITIZE_URL);
        $DB = new Database();
        $record = $DB->from('seo_links')->select('controller,method,record_id')->where('url',$url)->first();
        if($record) return [$record['controller'],$record['method'],$record['record_id']];
        return false;
    }
}
?>