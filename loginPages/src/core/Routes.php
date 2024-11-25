<?php
class Routes {
    private $controllerFile = 'login';
    private $controllerMethod = 'index';
    private $parameter = [];

    public function run() {
        $url = $this->getUrl();
        
        // set base URL for assets
        define('BASE_URL', '/public');

        if ($url && file_exists(__DIR__ . '/../controllers/' . $url[0] . '.php')) {
            $this->controllerFile = $url[0];
            unset($url[0]);
        }

        require_once __DIR__ . '/../controllers/' . $this->controllerFile . '.php';
        $controllerInstance = new $this->controllerFile();
        
        if (isset($url[1])) {
            if (method_exists($controllerInstance, $url[1])) {
                $this->controllerMethod = $url[1];
                unset($url[1]);
            }
        }

        $this->parameter = !empty($url) ? array_values($url) : [];
        call_user_func_array([$controllerInstance, $this->controllerMethod], $this->parameter);
    }

    private function getUrl() {
        return isset($_SERVER['REDIRECT_QUERY_STRING']) 
            ? explode('/', rtrim(filter_var($_SERVER['REDIRECT_QUERY_STRING'], FILTER_SANITIZE_URL), '/'))
            : false;
    }
}