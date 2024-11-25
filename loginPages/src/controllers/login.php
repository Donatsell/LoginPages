<?php
class login {
    public function index() {
        $this->renderLoginView();
    }

    public function register() {
        $this->renderRegisterView();
    }

    public function forgotPassword() {
        $this->renderForgotPasswordView();
    }

    private function renderLoginView() {
        require_once __DIR__ . '/../views/login/login.view.php';
    }

    private function renderRegisterView() {
        require_once __DIR__ . '/../views/login/register.view.php';
    }
    private function renderForgotPasswordView() {
        require_once __DIR__ . '/../views/login/forgot-password.view.php';
    }
}