<?php
use App\Helpers\Auth;
use App\Helpers\Response;

$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="pt" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Métricas - <?= APP_NAME ?></title>
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
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-3xl font-bold">Métricas de Uso</h1>
                        <p class="text-gray-600 dark:text-gray-400">Análise de aberturas de apps</p>
                    </div>
                    
                    <!-- Period Filter -->
                    <div class="flex gap-2">
                        <a href="/admin/metrics?period=7" 
                           class="px-4 py-2 rounded-lg <?= $period === 7 ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600' ?> transition">
                            7 dias
                        </a>
                        <a href="/admin/metrics?period=30" 
                           class="px-4 py-2 rounded-lg <?= $period === 30 ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600' ?> transition">
                            30 dias
                        </a>
                        <a href="/admin/metrics?period=90" 
                           class="px-4 py-2 rounded-lg <?= $period === 90 ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600' ?> transition">
                            90 dias
                        </a>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                <i data-lucide="activity" class="w-6 h-6 text-primary-600 dark:text-primary-400"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-bold"><?= number_format($summary['total_opens']) ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total de Aberturas</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <i data-lucide="grid" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-bold"><?= $summary['unique_apps'] ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Apps Únicos</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <i data-lucide="users" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-bold"><?= $summary['unique_users'] ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Usuários Ativos</p>
                    </div>
                </div>

                <!-- Top Apps -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 mb-8">
                    <h2 class="text-xl font-bold mb-6">Top 10 Apps Mais Usados</h2>
                    
                    <?php if (empty($topApps)): ?>
                        <p class="text-gray-500 text-center py-8">Nenhum dado disponível</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php 
                            $maxCount = $topApps[0]['open_count'] ?? 1;
                            foreach ($topApps as $index => $app): 
                                $percentage = ($app['open_count'] / $maxCount) * 100;
                            ?>
                                <div class="relative">
                                    <div class="flex items-center gap-4 relative z-10">
                                        <span class="text-2xl font-bold text-gray-400 w-8">#<?= $index + 1 ?></span>
                                        
                                        <?php if (!empty($app['icon'])): ?>
                                            <img src="<?= htmlspecialchars($app['icon']) ?>" alt="" class="w-12 h-12 rounded-lg">
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center text-white font-semibold">
                                                <?= strtoupper(substr($app['name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-lg"><?= htmlspecialchars($app['name']) ?></h3>
                                            <p class="text-sm text-gray-500"><?= $app['open_count'] ?> aberturas</p>
                                        </div>
                                        
                                        <div class="text-right">
                                            <span class="text-3xl font-bold text-primary-500"><?= $app['open_count'] ?></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="absolute inset-0 bg-primary-50 dark:bg-primary-900/20 rounded-lg" style="width: <?= $percentage ?>%;"></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Daily Opens Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold mb-6">Aberturas por Dia</h2>
                    
                    <?php if (empty($dailyOpens)): ?>
                        <p class="text-gray-500 text-center py-8">Nenhum dado disponível</p>
                    <?php else: ?>
                        <?php
                        $maxDaily = max(array_column($dailyOpens, 'count'));
                        ?>
                        <div class="space-y-3">
                            <?php foreach ($dailyOpens as $day): 
                                $percentage = $maxDaily > 0 ? ($day['count'] / $maxDaily) * 100 : 0;
                            ?>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm font-semibold w-24 text-gray-600 dark:text-gray-400">
                                        <?= date('d/m/Y', strtotime($day['date'])) ?>
                                    </span>
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-8 overflow-hidden">
                                        <div class="bg-primary-500 h-full flex items-center justify-end px-3 rounded-full transition-all" 
                                             style="width: <?= $percentage ?>%;">
                                            <?php if ($percentage > 15): ?>
                                                <span class="text-white font-semibold text-sm"><?= $day['count'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if ($percentage <= 15): ?>
                                        <span class="text-sm font-semibold w-12 text-right"><?= $day['count'] ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
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
