<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Helpers\Security;
use App\Models\User;

/**
 * Controller de Autenticação
 */
class AuthController
{
    /**
     * GET /signin - Exibe formulário de login
     */
    public static function showSignIn(): void
    {
        Auth::guest();
        Response::view('auth/signin');
    }

    /**
     * POST /signin - Processa login
     */
    public static function signIn(): void
    {
        Auth::guest();
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validação
        if (empty($email) || empty($password)) {
            Response::flash('error', 'Email e senha são obrigatórios.');
            Response::redirect('/signin');
        }
        
        // Rate limiting
        if (!Security::checkRateLimit($email)) {
            Response::flash('error', 'Muitas tentativas de login. Tente novamente em 15 minutos.');
            Response::redirect('/signin');
        }
        
        // Busca usuário
        $user = User::findByEmail($email);
        
        if (!$user || !User::verifyPassword($password, $user['password_hash'])) {
            Response::flash('error', 'Email ou senha incorretos.');
            Response::redirect('/signin');
        }
        
        // Limpa rate limit e faz login
        Security::clearRateLimit($email);
        Auth::login($user);
        
        // Redireciona
        $redirect = $_GET['redirect'] ?? '/';
        Response::redirect($redirect);
    }

    /**
     * GET /signup - Exibe formulário de registro
     */
    public static function showSignUp(): void
    {
        Auth::guest();
        Response::view('auth/signup');
    }

    /**
     * POST /signup - Processa registro
     */
    public static function signUp(): void
    {
        Auth::guest();
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validação
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Nome é obrigatório.';
        }
        
        if (empty($email) || !Security::isValidEmail($email)) {
            $errors[] = 'Email inválido.';
        }
        
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            $errors[] = 'A senha deve ter no mínimo ' . PASSWORD_MIN_LENGTH . ' caracteres.';
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = 'As senhas não coincidem.';
        }
        
        // Verifica se email já existe
        if (User::findByEmail($email)) {
            $errors[] = 'Este email já está registrado.';
        }
        
        if (!empty($errors)) {
            Response::flash('error', implode('<br>', $errors));
            Response::redirect('/signup');
        }
        
        // Cria usuário
        try {
            $userId = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => 'USER'
            ]);
            
            $user = User::findById($userId);
            Auth::login($user);
            
            Response::flash('success', 'Conta criada com sucesso!');
            Response::redirect('/');
        } catch (\Exception $e) {
            Response::flash('error', 'Erro ao criar conta. Tente novamente.');
            Response::redirect('/signup');
        }
    }

    /**
     * POST /logout - Faz logout
     */
    public static function logout(): void
    {
        Auth::logout();
        Response::redirect('/signin');
    }
}
