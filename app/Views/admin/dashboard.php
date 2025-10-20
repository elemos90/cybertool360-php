<?php
use App\Helpers\Auth;
use App\Helpers\Response;

$user = Auth::user();
$success = Response::getFlash('success');
$error = Response::getFlash('error');
?>
<!DOCTYPE html>
<html lang="pt" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    
    <?php include __DIR__ . '/_navbar.php'; ?>

    <div class="flex">
        <?php include __DIR__ . '/_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="max-w-7xl mx-auto">
                
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold">Dashboard</h1>
                    <p class="text-gray-600 dark:text-gray-400">Visão geral do sistema</p>
                </div>

                <?php if ($success): ?>
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <p class="text-sm text-green-600 dark:text-green-400"><?= $success ?></p>
                    </div>
                <?php endif; ?>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Apps -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                <i data-lucide="grid" class="w-6 h-6 text-primary-600 dark:text-primary-400"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold"><?= $stats['apps'] ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Apps Ativos</p>
                        <p class="text-xs text-gray-500 mt-1">Total: <?= $stats['total_apps'] ?></p>
                    </div>

                    <!-- Categories -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <i data-lucide="folder" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold"><?= $stats['categories'] ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Categorias</p>
                    </div>

                    <!-- Users -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <i data-lucide="users" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold"><?= $stats['users'] ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Usuários</p>
                    </div>

                    <!-- Opens (7d) -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                                <i data-lucide="activity" class="w-6 h-6 text-orange-600 dark:text-orange-400"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold"><?= $metrics7d['total_opens'] ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Aberturas (7d)</p>
                        <p class="text-xs text-gray-500 mt-1">Usuários únicos: <?= $metrics7d['unique_users'] ?></p>
                    </div>
                </div>

                <!-- Top Apps -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 mb-8">
                    <h2 class="text-xl font-bold mb-4">Top 5 Apps (Últimos 7 Dias)</h2>
                    
                    <?php if (empty($topApps7d)): ?>
                        <p class="text-gray-500">Nenhum dado disponível</p>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($topApps7d as $app): ?>
                                <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <?php if (!empty($app['icon'])): ?>
                                        <img src="<?= htmlspecialchars($app['icon']) ?>" alt="" class="w-10 h-10 rounded-lg">
                                    <?php else: ?>
                                        <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center text-white font-semibold">
                                            <?= strtoupper(substr($app['name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-1">
                                        <h3 class="font-semibold"><?= htmlspecialchars($app['name']) ?></h3>
                                        <p class="text-sm text-gray-500"><?= $app['open_count'] ?> aberturas</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-2xl font-bold text-primary-500"><?= $app['open_count'] ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="/admin/apps" class="block bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:border-primary-500 transition">
                        <i data-lucide="grid" class="w-8 h-8 text-primary-500 mb-3"></i>
                        <h3 class="text-lg font-semibold mb-2">Gerenciar Apps</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Criar, editar e remover apps</p>
                    </a>

                    <a href="/admin/categories" class="block bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:border-primary-500 transition">
                        <i data-lucide="folder" class="w-8 h-8 text-purple-500 mb-3"></i>
                        <h3 class="text-lg font-semibold mb-2">Gerenciar Categorias</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Organizar apps por categorias</p>
                    </a>

                    <?php if (Auth::isAdmin()): ?>
                        <a href="/admin/users" class="block bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:border-primary-500 transition">
                            <i data-lucide="users" class="w-8 h-8 text-green-500 mb-3"></i>
                            <h3 class="text-lg font-semibold mb-2">Gerenciar Usuários</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Administrar usuários e permissões</p>
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
