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
    <title>Gerenciar Categorias - <?= APP_NAME ?></title>
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
                        <h1 class="text-3xl font-bold">Gerenciar Categorias</h1>
                        <p class="text-gray-600 dark:text-gray-400">Total: <?= count($categories) ?> categorias</p>
                    </div>
                    <button @click="$refs.createModal.classList.remove('hidden')" 
                            class="px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-semibold transition">
                        <i data-lucide="plus" class="w-5 h-5 inline mr-2"></i>
                        Nova Categoria
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

                <!-- Categories Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($categories as $cat): ?>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($cat['icon'])): ?>
                                        <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                            <i data-lucide="<?= htmlspecialchars($cat['icon']) ?>" class="w-6 h-6 text-primary-600 dark:text-primary-400"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h3 class="font-bold text-lg"><?= htmlspecialchars($cat['name']) ?></h3>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($cat['slug']) ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <?= $cat['app_count'] ?> app(s)
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-400 ml-4">
                                    Ordem: <?= $cat['order'] ?>
                                </span>
                            </div>
                            
                            <div class="flex gap-2">
                                <button onclick="editCategory(<?= htmlspecialchars(json_encode($cat)) ?>)" 
                                        class="flex-1 py-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg text-sm font-semibold">
                                    <i data-lucide="edit-2" class="w-4 h-4 inline mr-1"></i>
                                    Editar
                                </button>
                                <button onclick="deleteCategory('<?= $cat['id'] ?>', '<?= htmlspecialchars($cat['name']) ?>')" 
                                        class="flex-1 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg text-sm font-semibold">
                                    <i data-lucide="trash-2" class="w-4 h-4 inline mr-1"></i>
                                    Remover
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </main>
    </div>

    <!-- Create Modal -->
    <div x-ref="createModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl max-w-lg w-full">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold">Criar Nova Categoria</h2>
            </div>
            <form action="/admin/categories/create" method="POST" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Nome *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2">Slug</label>
                    <input type="text" name="slug" placeholder="Deixe vazio para gerar automaticamente" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2">Ícone (Lucide)</label>
                    <input type="text" name="icon" placeholder="Ex: folder, briefcase, code" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                    <p class="text-xs text-gray-500 mt-1">Ver ícones em: <a href="https://lucide.dev/icons" target="_blank" class="text-primary-500">lucide.dev</a></p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2">Ordem</label>
                    <input type="number" name="order" value="0" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 py-3 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-semibold">
                        Criar Categoria
                    </button>
                    <button type="button" @click="$refs.createModal.classList.add('hidden')" class="flex-1 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg font-semibold">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editCategory(cat) {
            alert('Edit functionality - implement modal with pre-filled form');
        }
        
        async function deleteCategory(id, name) {
            if (!confirm(`Tem certeza que deseja remover "${name}"?`)) return;
            
            try {
                const response = await fetch('/admin/categories/delete', {
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
                alert('Erro ao remover categoria.');
            }
        }
        
        lucide.createIcons();
    </script>
</body>
</html>
