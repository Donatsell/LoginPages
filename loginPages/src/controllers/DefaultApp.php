<?php
class DefaultApp {
    public function index() {
        header('Location: /login');
        exit();
    }
}
