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
    <title>Gerenciar Usuários - <?= APP_NAME ?></title>
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
                        <h1 class="text-3xl font-bold">Gerenciar Usuários</h1>
                        <p class="text-gray-600 dark:text-gray-400">Total: <?= count($users) ?> usuários</p>
                    </div>
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

                <!-- Users Table -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Usuário</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Nível</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Cadastro</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <?php foreach ($users as $u): ?>
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center text-white font-semibold">
                                                    <?= strtoupper(substr($u['name'] ?? 'U', 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <p class="font-semibold"><?= htmlspecialchars($u['name'] ?? 'Sem nome') ?></p>
                                                    <?php if ($u['id'] === Auth::id()): ?>
                                                        <span class="text-xs text-primary-500">(Você)</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm"><?= htmlspecialchars($u['email']) ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($u['id'] === Auth::id()): ?>
                                                <span class="text-xs px-3 py-1 rounded <?= $u['role'] === 'ADMIN' ? 'bg-red-100 text-red-700' : ($u['role'] === 'MANAGER' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') ?>">
                                                    <?= $u['role'] ?>
                                                </span>
                                            <?php else: ?>
                                                <select onchange="updateRole('<?= $u['id'] ?>', this.value)" 
                                                        class="text-xs px-3 py-1 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                                                    <option value="USER" <?= $u['role'] === 'USER' ? 'selected' : '' ?>>USER</option>
                                                    <option value="MANAGER" <?= $u['role'] === 'MANAGER' ? 'selected' : '' ?>>MANAGER</option>
                                                    <option value="ADMIN" <?= $u['role'] === 'ADMIN' ? 'selected' : '' ?>>ADMIN</option>
                                                </select>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-500">
                                                <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($u['id'] !== Auth::id()): ?>
                                                <button onclick="deleteUser('<?= $u['id'] ?>', '<?= htmlspecialchars($u['email']) ?>')" 
                                                        class="p-2 text-red-600 hover:bg-red-50 rounded">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-sm text-blue-600 dark:text-blue-400">
                        <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                        <strong>Níveis de Acesso:</strong>
                        <span class="ml-2">USER = Apenas navegação</span>
                        <span class="ml-2">MANAGER = Gerenciar apps e categorias</span>
                        <span class="ml-2">ADMIN = Acesso total</span>
                    </p>
                </div>

            </div>
        </main>
    </div>

    <script>
        async function updateRole(userId, role) {
            try {
                const response = await fetch('/admin/users/update-role', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${userId}&role=${role}`
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                    location.reload();
                }
            } catch (error) {
                alert('Erro ao atualizar nível de acesso.');
                location.reload();
            }
        }
        
        async function deleteUser(userId, email) {
            if (!confirm(`Tem certeza que deseja remover o usuário "${email}"?`)) return;
            
            try {
                const response = await fetch('/admin/users/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${userId}`
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Erro ao remover usuário.');
            }
        }
        
        lucide.createIcons();
    </script>
</body>
</html>
