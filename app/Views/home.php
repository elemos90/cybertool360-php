<?php
use App\Helpers\Auth;
use App\Helpers\Security;

$user = Auth::user();
$csrfToken = Security::generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="pt" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Launcher</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">
    
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">CT</span>
                    </div>
                    <h1 class="text-xl font-bold"><?= APP_NAME ?></h1>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i data-lucide="sun" class="w-5 h-5" x-show="!darkMode"></i>
                        <i data-lucide="moon" class="w-5 h-5" x-show="darkMode" x-cloak></i>
                    </button>
                    
                    <?php if (Auth::isManager()): ?>
                    <a href="/admin" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i data-lucide="settings" class="w-5 h-5"></i>
                    </a>
                    <?php endif; ?>
                    
                    <!-- User Menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center text-white font-semibold">
                                <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                            </div>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1">
                            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-semibold"><?= htmlspecialchars($user['name'] ?? 'Usuário') ?></p>
                                <p class="text-xs text-gray-500"><?= htmlspecialchars($user['email']) ?></p>
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

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Search & Filters -->
        <div class="mb-8">
            <form method="GET" action="/" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
                           placeholder="Pesquisar apps..." 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <button type="submit" class="px-6 py-3 bg-primary-500 text-white rounded-xl hover:bg-primary-600 transition font-semibold">
                    <i data-lucide="search" class="w-5 h-5 inline"></i>
                    Pesquisar
                </button>
            </form>
            
            <!-- Categories Filter -->
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="/" class="px-4 py-2 rounded-lg <?= empty($categoryFilter) ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600' ?> transition">
                    Todos
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="/?category=<?= urlencode($cat['id']) ?>" 
                       class="px-4 py-2 rounded-lg <?= $categoryFilter === $cat['id'] ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600' ?> transition">
                        <?php if ($cat['icon']): ?>
                            <i data-lucide="<?= htmlspecialchars($cat['icon']) ?>" class="w-4 h-4 inline mr-1"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($cat['name']) ?>
                        <span class="text-xs opacity-75">(<?= $cat['app_count'] ?>)</span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Apps Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <?php if (empty($apps)): ?>
                <div class="col-span-full text-center py-12 text-gray-500">
                    <i data-lucide="inbox" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                    <p class="text-lg">Nenhum app encontrado</p>
                </div>
            <?php else: ?>
                <?php foreach ($apps as $app): ?>
                    <div x-data="{ showMenu: false }" class="relative group">
                        <!-- Card -->
                        <a href="/internal?slug=<?= urlencode($app['slug']) ?>" 
                           class="block bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all border border-gray-200 dark:border-gray-700 hover:scale-105">
                            
                            <!-- Pin Badge -->
                            <?php if ($app['is_pinned']): ?>
                                <div class="absolute top-2 right-2 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
                                    <i data-lucide="star" class="w-4 h-4 text-yellow-900 fill-current"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Icon -->
                            <div class="aspect-square mb-3 flex items-center justify-center">
                                <?php if (!empty($app['icon'])): ?>
                                    <img src="<?= htmlspecialchars($app['icon']) ?>" alt="<?= htmlspecialchars($app['name']) ?>" 
                                         class="w-16 h-16 object-contain">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold">
                                        <?= strtoupper(substr($app['name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Name -->
                            <h3 class="text-sm font-semibold text-center truncate"><?= htmlspecialchars($app['name']) ?></h3>
                            
                            <!-- External Badge -->
                            <?php if ($app['open_mode'] === 'EXTERNAL'): ?>
                                <div class="mt-1 text-xs text-center text-gray-500">
                                    <i data-lucide="external-link" class="w-3 h-3 inline"></i>
                                </div>
                            <?php endif; ?>
                        </a>
                        
                        <!-- Menu Button -->
                        <button @click="showMenu = !showMenu" 
                                class="absolute top-2 left-2 w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg opacity-0 group-hover:opacity-100 hover:bg-gray-200 dark:hover:bg-gray-600 transition flex items-center justify-center">
                            <i data-lucide="more-vertical" class="w-4 h-4"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="showMenu" @click.away="showMenu = false" x-cloak
                             class="absolute top-12 left-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10 min-w-[160px]">
                            <a href="/internal?slug=<?= urlencode($app['slug']) ?>" 
                               class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="square" class="w-4 h-4 inline mr-2"></i>
                                Abrir Interno
                            </a>
                            <a href="/open?slug=<?= urlencode($app['slug']) ?>" target="_blank"
                               class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="external-link" class="w-4 h-4 inline mr-2"></i>
                                Nova Aba
                            </a>
                            <button onclick="togglePin('<?= $app['id'] ?>', this)" 
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="star" class="w-4 h-4 inline mr-2"></i>
                                <?= $app['is_pinned'] ? 'Desfavoritar' : 'Favoritar' ?>
                            </button>
                            <button onclick="copyLink('<?= htmlspecialchars($app['url']) ?>')" 
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="copy" class="w-4 h-4 inline mr-2"></i>
                                Copiar Link
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Toggle Pin
        async function togglePin(appId, btn) {
            try {
                const response = await fetch('/pins/toggle', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `app_id=${appId}`
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Erro ao atualizar favorito.');
            }
        }
        
        // Copy Link
        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('Link copiado!');
            });
        }
        
        // Initialize Lucide
        lucide.createIcons();
    </script>
</body>
</html>
