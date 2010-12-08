<?php
class Vpfw_Request_Http implements Vpfw_Request_Interface {
    private $parameters;

    public function __construct() {
        $this->parameters = $_REQUEST;
    }

    public function issetParameter($name) {
        return isset($this->parameters[$name]);
    }

    public function getParameter($name) {
        if (true == isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }
        return null;
    }

    public function getParameterNames() {
        return array_keys($this->parameters);
    }

    public function getHeader($name) {
        $name = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        if (true == isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
        return null;
    }

    public function getRemoteAddress() {
        return $_SERVER['REMOTE_ADDR'];
    }

    public static function url($controller, $action = 'index', $parameters = array()) {
        $retUrl = 'index.php?cOn=' . urlencode($controller) . '&aCt=' . urlencode($action);
        foreach ($parameters as $key => $value) {
            $retUrl .= '&' . urlencode($key) . '=' . urlencode($value);
        }
        return $retUrl;
    }
}
