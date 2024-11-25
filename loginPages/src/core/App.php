
<?php
class App {
    private $routes;
    
    public function __construct() {
        $this->routes = new Routes();
    }
    
    public function run() {
        $this->routes->run();
    }
}
