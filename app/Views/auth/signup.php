<?php
use App\Helpers\Response;

$error = Response::getFlash('error');
?>
<!DOCTYPE html>
<html lang="pt" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen flex items-center justify-center px-4">
    
    <div class="w-full max-w-md">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <span class="text-white font-bold text-2xl">CT</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><?= APP_NAME ?></h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Crie sua conta</p>
        </div>

        <!-- Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700">
            
            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-sm text-red-600 dark:text-red-400"><?= $error ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="/signup">
                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Nome
                    </label>
                    <input type="text" id="name" name="name" required autofocus
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Email
                    </label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Senha
                    </label>
                    <input type="password" id="password" name="password" required minlength="8"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    <p class="mt-1 text-xs text-gray-500">Mínimo de 8 caracteres</p>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="confirm_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Confirmar Senha
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                </div>

                <!-- Submit -->
                <button type="submit" 
                        class="w-full py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-lg transition shadow-lg hover:shadow-xl">
                    Criar Conta
                </button>
            </form>

            <!-- Signin Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Já tem uma conta? 
                    <a href="/signin" class="text-primary-500 hover:text-primary-600 font-semibold">
                        Faça login
                    </a>
                </p>
            </div>
        </div>

        <!-- Dark Mode Toggle -->
        <div class="mt-6 text-center">
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <i data-lucide="sun" class="w-4 h-4" x-show="!darkMode"></i>
                <i data-lucide="moon" class="w-4 h-4" x-show="darkMode" x-cloak></i>
                <span x-text="darkMode ? 'Modo Claro' : 'Modo Escuro'"></span>
            </button>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
