<?php
use App\Helpers\Auth;
use App\Helpers\Response;
use App\Helpers\Security;

$user = Auth::user();
$success = Response::getFlash('success');
$error = Response::getFlash('error');
?>
<!DOCTYPE html>
<html lang="pt" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Apps - <?= APP_NAME ?></title>
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
                        <h1 class="text-3xl font-bold">Gerenciar Apps</h1>
                        <p class="text-gray-600 dark:text-gray-400">Total: <?= count($apps) ?> apps</p>
                    </div>
                    <button @click="$refs.createModal.classList.remove('hidden')" 
                            class="px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-semibold transition">
                        <i data-lucide="plus" class="w-5 h-5 inline mr-2"></i>
                        Novo App
                    </button>
                </div>

                <?php if ($success): ?>
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <p class="text-sm text-green-600 dark:text-green-400"><?= $success ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-sm text-red-600 dark:text-red-400"><?= $error ?></p>
                    </div>
                <?php endif; ?>

                <!-- Apps Table -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">App</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Categoria</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Modo</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <?php foreach ($apps as $app): ?>
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <?php if (!empty($app['icon'])): ?>
                                                    <img src="<?= htmlspecialchars($app['icon']) ?>" alt="" class="w-10 h-10 rounded-lg">
                                                <?php else: ?>
                                                    <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center text-white font-semibold">
                                                        <?= strtoupper(substr($app['name'], 0, 1)) ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <p class="font-semibold"><?= htmlspecialchars($app['name']) ?></p>
                                                    <p class="text-xs text-gray-500"><?= htmlspecialchars($app['slug']) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm"><?= htmlspecialchars($app['category_name'] ?? '-') ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="<?= htmlspecialchars($app['url']) ?>" target="_blank" class="text-sm text-primary-500 hover:underline truncate max-w-xs block">
                                                <?= htmlspecialchars($app['url']) ?>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs px-2 py-1 rounded <?= $app['open_mode'] === 'INTERNAL' ? 'bg-blue-100 text-blue-700' : ($app['open_mode'] === 'EXTERNAL' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700') ?>">
                                                <?= $app['open_mode'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs px-2 py-1 rounded <?= $app['active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' ?>">
                                                <?= $app['active'] ? 'Ativo' : 'Inativo' ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <button onclick="editApp(<?= htmlspecialchars(json_encode($app)) ?>)" 
                                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded">
                                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                                </button>
                                                <button onclick="deleteApp('<?= $app['id'] ?>', '<?= htmlspecialchars($app['name']) ?>')" 
                                                        class="p-2 text-red-600 hover:bg-red-50 rounded">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Create Modal -->
    <div x-ref="createModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold">Criar Novo App</h2>
            </div>
            <form action="/admin/apps/create" method="POST" class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Nome *</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Slug *</label>
                        <input type="text" name="slug" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2">URL *</label>
                    <input type="url" name="url" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2">Descrição</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Categoria *</label>
                        <select name="category_id" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                            <option value="">Selecione...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Modo de Abertura</label>
                        <select name="open_mode" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                            <option value="INTERNAL">Internal</option>
                            <option value="EXTERNAL">External</option>
                            <option value="SMART">Smart</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2">Allowlist Domains (separados por vírgula)</label>
                    <input type="text" name="allowlist_domains" placeholder="example.com, *.example.com" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">URL do Ícone</label>
                        <input type="url" name="icon" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Ordem</label>
                        <input type="number" name="order" value="0" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2">Tags (separadas por vírgula)</label>
                    <input type="text" name="tags" placeholder="tag1, tag2, tag3" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                </div>
                
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="active" checked class="rounded">
                        <span class="text-sm font-semibold">Ativo</span>
                    </label>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 py-3 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-semibold">
                        Criar App
                    </button>
                    <button type="button" @click="$refs.createModal.classList.add('hidden')" class="flex-1 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg font-semibold">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editApp(app) {
            // Simplified edit - in production, use a proper modal
            alert('Edit functionality - implement modal with pre-filled form');
        }
        
        async function deleteApp(id, name) {
            if (!confirm(`Tem certeza que deseja remover "${name}"?`)) return;
            
            try {
                const response = await fetch('/admin/apps/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Erro ao remover app.');
            }
        }
        
        lucide.createIcons();
    </script>
</body>
</html>
