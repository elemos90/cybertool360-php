<?php
use App\Helpers\Auth;

$currentPath = $_SERVER['REQUEST_URI'];
$isActive = fn($path) => str_starts_with($currentPath, $path) ? 'bg-primary-50 dark:bg-primary-900 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700';
?>
<aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 min-h-screen">
    <nav class="p-4 space-y-1">
        <a href="/admin" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= $isActive('/admin') ?>">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        
        <a href="/admin/apps" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= $isActive('/admin/apps') ?>">
            <i data-lucide="grid" class="w-5 h-5"></i>
            <span class="font-medium">Apps</span>
        </a>
        
        <a href="/admin/categories" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= $isActive('/admin/categories') ?>">
            <i data-lucide="folder" class="w-5 h-5"></i>
            <span class="font-medium">Categorias</span>
        </a>
        
        <?php if (Auth::isAdmin()): ?>
            <a href="/admin/users" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= $isActive('/admin/users') ?>">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="font-medium">Usuários</span>
            </a>
            
            <a href="/admin/metrics" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= $isActive('/admin/metrics') ?>">
                <i data-lucide="bar-chart" class="w-5 h-5"></i>
                <span class="font-medium">Métricas</span>
            </a>
        <?php endif; ?>
    </nav>
</aside>
