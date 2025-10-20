<?php
use App\Helpers\Auth;

$user = Auth::user();
?>
<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">CT</span>
                </div>
                <h1 class="text-xl font-bold"><?= APP_NAME ?> Admin</h1>
            </div>
            
            <!-- Right Actions -->
            <div class="flex items-center gap-2">
                <!-- Back to Home -->
                <a href="/" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Voltar ao Launcher">
                    <i data-lucide="home" class="w-5 h-5"></i>
                </a>
                
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i data-lucide="sun" class="w-5 h-5" x-show="!darkMode"></i>
                    <i data-lucide="moon" class="w-5 h-5" x-show="darkMode" x-cloak></i>
                </button>
                
                <!-- User Menu -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center text-white font-semibold">
                            <?= strtoupper(substr($user['name'] ?? 'A', 0, 1)) ?>
                        </div>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1">
                        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-sm font-semibold"><?= htmlspecialchars($user['name'] ?? 'Admin') ?></p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars($user['role']) ?></p>
                        </div>
                        <form action="/logout" method="POST">
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 dark:text-red-400">
                                <i data-lucide="log-out" class="w-4 h-4 inline mr-2"></i>
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
