<?php
use App\Helpers\Auth;
use App\Helpers\Security;

$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="pt" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', fullscreen: false, viewport: 'full' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($app['name']) ?> - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        .viewport-414 { max-width: 414px; margin: 0 auto; }
        .viewport-768 { max-width: 768px; margin: 0 auto; }
        .viewport-1024 { max-width: 1024px; margin: 0 auto; }
        .viewport-full { width: 100%; }
        
        .iframe-container { 
            position: relative; 
            width: 100%; 
            height: calc(100vh - 60px);
        }
        
        .iframe-container.fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            height: 100vh;
        }
        
        .iframe-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    
    <!-- Topbar -->
    <div x-show="!fullscreen" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-full mx-auto px-4">
            <div class="flex items-center justify-between h-[60px] gap-2">
                
                <!-- Left Actions -->
                <div class="flex items-center gap-2">
                    <a href="/" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Voltar">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                    
                    <a href="/" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Home">
                        <i data-lucide="home" class="w-5 h-5"></i>
                    </a>
                    
                    <button onclick="reloadIframe()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Recarregar">
                        <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <!-- Center - App Info -->
                <div class="flex-1 flex items-center gap-2 min-w-0">
                    <?php if (!empty($app['icon'])): ?>
                        <img src="<?= htmlspecialchars($app['icon']) ?>" alt="" class="w-6 h-6 flex-shrink-0">
                    <?php endif; ?>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-sm font-semibold truncate"><?= htmlspecialchars($app['name']) ?></h1>
                        <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($app['url']) ?></p>
                    </div>
                </div>
                
                <!-- Right Actions -->
                <div class="flex items-center gap-2">
                    <!-- Viewport Selector -->
                    <div x-data="{ open: false }" class="relative hidden md:block">
                        <button @click="open = !open" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Viewport">
                            <i data-lucide="monitor" class="w-5 h-5"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1">
                            <button @click="viewport = 'full'; open = false" 
                                    :class="viewport === 'full' ? 'bg-primary-50 dark:bg-primary-900' : ''"
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="monitor" class="w-4 h-4 inline mr-2"></i>
                                Desktop (Full)
                            </button>
                            <button @click="viewport = '1024'; open = false" 
                                    :class="viewport === '1024' ? 'bg-primary-50 dark:bg-primary-900' : ''"
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="tablet" class="w-4 h-4 inline mr-2"></i>
                                Tablet (1024px)
                            </button>
                            <button @click="viewport = '768'; open = false" 
                                    :class="viewport === '768' ? 'bg-primary-50 dark:bg-primary-900' : ''"
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="tablet" class="w-4 h-4 inline mr-2"></i>
                                Tablet (768px)
                            </button>
                            <button @click="viewport = '414'; open = false" 
                                    :class="viewport === '414' ? 'bg-primary-50 dark:bg-primary-900' : ''"
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="smartphone" class="w-4 h-4 inline mr-2"></i>
                                Mobile (414px)
                            </button>
                        </div>
                    </div>
                    
                    <!-- Open in New Tab -->
                    <a href="<?= htmlspecialchars($app['url']) ?>" target="_blank" 
                       class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Abrir em nova aba">
                        <i data-lucide="external-link" class="w-5 h-5"></i>
                    </a>
                    
                    <!-- Fullscreen Toggle -->
                    <button @click="fullscreen = !fullscreen" 
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Tela cheia">
                        <i data-lucide="maximize" class="w-5 h-5" x-show="!fullscreen"></i>
                        <i data-lucide="minimize" class="w-5 h-5" x-show="fullscreen" x-cloak></i>
                    </button>
                    
                    <!-- Pin/Unpin -->
                    <button onclick="togglePin('<?= $app['id'] ?>')" 
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="<?= $isPinned ? 'Desfavoritar' : 'Favoritar' ?>">
                        <i data-lucide="star" class="w-5 h-5 <?= $isPinned ? 'fill-yellow-400 text-yellow-400' : '' ?>"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Iframe Container -->
    <div :class="'iframe-container viewport-' + viewport + (fullscreen ? ' fullscreen' : '')">
        <iframe id="appFrame" 
                src="<?= htmlspecialchars($app['url']) ?>" 
                sandbox="allow-scripts allow-same-origin allow-forms allow-popups allow-popups-to-escape-sandbox"
                referrerpolicy="no-referrer"
                loading="lazy">
        </iframe>
        
        <!-- Exit Fullscreen Button -->
        <button x-show="fullscreen" @click="fullscreen = false"
                class="absolute top-4 right-4 p-3 bg-black/50 hover:bg-black/70 text-white rounded-lg transition z-10" x-cloak>
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <script>
        function reloadIframe() {
            document.getElementById('appFrame').src = document.getElementById('appFrame').src;
        }
        
        async function togglePin(appId) {
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
        
        // Esc key to exit fullscreen
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && Alpine.store) {
                // Trigger Alpine to close fullscreen
            }
        });
        
        lucide.createIcons();
    </script>
</body>
</html>
