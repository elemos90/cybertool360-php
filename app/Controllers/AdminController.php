<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Helpers\Security;
use App\Models\App;
use App\Models\Category;
use App\Models\User;
use App\Models\Metric;

/**
 * Controller Admin
 */
class AdminController
{
    /**
     * GET /admin - Dashboard
     */
    public static function dashboard(): void
    {
        Auth::requireManager();
        
        $stats = [
            'apps' => App::count(true),
            'total_apps' => App::count(false),
            'categories' => Category::count(),
            'users' => User::count(),
        ];
        
        // Métricas (últimos 7 dias)
        $metrics7d = Metric::summary(7);
        $topApps7d = Metric::topApps(7, 5);
        $dailyOpens = Metric::dailyOpens(7);
        
        Response::view('admin/dashboard', [
            'stats' => $stats,
            'metrics7d' => $metrics7d,
            'topApps7d' => $topApps7d,
            'dailyOpens' => $dailyOpens
        ]);
    }

    // ========== APPS ==========

    /**
     * GET /admin/apps - Lista apps
     */
    public static function listApps(): void
    {
        Auth::requireManager();
        
        $apps = App::all();
        $categories = Category::all();
        
        Response::view('admin/apps', [
            'apps' => $apps,
            'categories' => $categories
        ]);
    }

    /**
     * POST /admin/apps/create - Cria app
     */
    public static function createApp(): void
    {
        Auth::requireManager();
        
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $categoryId = trim($_POST['category_id'] ?? '');
        
        // Validação básica
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Nome é obrigatório.';
        }
        
        if (empty($slug)) {
            $slug = Security::slugify($name);
        }
        
        if (empty($url) || !Security::isValidUrl($url)) {
            $errors[] = 'URL inválida.';
        }
        
        if (empty($categoryId)) {
            $errors[] = 'Categoria é obrigatória.';
        }
        
        // Verifica se slug já existe
        if (App::findBySlug($slug)) {
            $errors[] = 'Este slug já está em uso.';
        }
        
        if (!empty($errors)) {
            Response::flash('error', implode('<br>', $errors));
            Response::redirect('/admin/apps');
        }
        
        // Cria app
        try {
            App::create([
                'name' => $name,
                'slug' => $slug,
                'description' => trim($_POST['description'] ?? ''),
                'url' => $url,
                'open_mode' => $_POST['open_mode'] ?? 'INTERNAL',
                'allowlist_domains' => trim($_POST['allowlist_domains'] ?? ''),
                'icon' => trim($_POST['icon'] ?? ''),
                'tags' => trim($_POST['tags'] ?? ''),
                'active' => isset($_POST['active']) ? 1 : 0,
                'order' => (int) ($_POST['order'] ?? 0),
                'category_id' => $categoryId
            ]);
            
            Response::flash('success', 'App criado com sucesso!');
            Response::redirect('/admin/apps');
        } catch (\Exception $e) {
            Response::flash('error', 'Erro ao criar app: ' . $e->getMessage());
            Response::redirect('/admin/apps');
        }
    }

    /**
     * POST /admin/apps/update - Atualiza app
     */
    public static function updateApp(): void
    {
        Auth::requireManager();
        
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            Response::jsonError('ID não especificado.', 400);
        }
        
        $app = App::findById($id);
        if (!$app) {
            Response::jsonError('App não encontrado.', 404);
        }
        
        // Atualiza
        try {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'url' => trim($_POST['url'] ?? ''),
                'open_mode' => $_POST['open_mode'] ?? 'INTERNAL',
                'allowlist_domains' => trim($_POST['allowlist_domains'] ?? ''),
                'icon' => trim($_POST['icon'] ?? ''),
                'tags' => trim($_POST['tags'] ?? ''),
                'active' => isset($_POST['active']) ? 1 : 0,
                'order' => (int) ($_POST['order'] ?? 0),
                'category_id' => trim($_POST['category_id'] ?? '')
            ];
            
            App::update($id, $data);
            
            Response::flash('success', 'App atualizado com sucesso!');
            Response::redirect('/admin/apps');
        } catch (\Exception $e) {
            Response::flash('error', 'Erro ao atualizar app: ' . $e->getMessage());
            Response::redirect('/admin/apps');
        }
    }

    /**
     * POST /admin/apps/delete - Remove app
     */
    public static function deleteApp(): void
    {
        Auth::requireManager();
        
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            Response::jsonError('ID não especificado.', 400);
        }
        
        try {
            App::delete($id);
            Response::jsonSuccess('App removido com sucesso!');
        } catch (\Exception $e) {
            Response::jsonError('Erro ao remover app: ' . $e->getMessage(), 500);
        }
    }

    // ========== CATEGORIAS ==========

    /**
     * GET /admin/categories - Lista categorias
     */
    public static function listCategories(): void
    {
        Auth::requireManager();
        
        $categories = Category::withAppCount(false);
        
        Response::view('admin/categories', [
            'categories' => $categories
        ]);
    }

    /**
     * POST /admin/categories/create - Cria categoria
     */
    public static function createCategory(): void
    {
        Auth::requireManager();
        
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        
        // Validação
        if (empty($name)) {
            Response::flash('error', 'Nome é obrigatório.');
            Response::redirect('/admin/categories');
        }
        
        if (empty($slug)) {
            $slug = Security::slugify($name);
        }
        
        // Verifica se slug já existe
        if (Category::findBySlug($slug)) {
            Response::flash('error', 'Este slug já está em uso.');
            Response::redirect('/admin/categories');
        }
        
        // Cria categoria
        try {
            Category::create([
                'name' => $name,
                'slug' => $slug,
                'icon' => trim($_POST['icon'] ?? ''),
                'order' => (int) ($_POST['order'] ?? 0)
            ]);
            
            Response::flash('success', 'Categoria criada com sucesso!');
            Response::redirect('/admin/categories');
        } catch (\Exception $e) {
            Response::flash('error', 'Erro ao criar categoria: ' . $e->getMessage());
            Response::redirect('/admin/categories');
        }
    }

    /**
     * POST /admin/categories/update - Atualiza categoria
     */
    public static function updateCategory(): void
    {
        Auth::requireManager();
        
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            Response::jsonError('ID não especificado.', 400);
        }
        
        try {
            Category::update($id, [
                'name' => trim($_POST['name'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'icon' => trim($_POST['icon'] ?? ''),
                'order' => (int) ($_POST['order'] ?? 0)
            ]);
            
            Response::flash('success', 'Categoria atualizada com sucesso!');
            Response::redirect('/admin/categories');
        } catch (\Exception $e) {
            Response::flash('error', 'Erro ao atualizar categoria: ' . $e->getMessage());
            Response::redirect('/admin/categories');
        }
    }

    /**
     * POST /admin/categories/delete - Remove categoria
     */
    public static function deleteCategory(): void
    {
        Auth::requireManager();
        
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            Response::jsonError('ID não especificado.', 400);
        }
        
        try {
            Category::delete($id);
            Response::jsonSuccess('Categoria removida com sucesso!');
        } catch (\Exception $e) {
            Response::jsonError('Não é possível remover esta categoria (pode ter apps associados).', 400);
        }
    }

    // ========== USUÁRIOS ==========

    /**
     * GET /admin/users - Lista usuários (apenas ADMIN)
     */
    public static function listUsers(): void
    {
        Auth::requireAdmin();
        
        $users = User::all();
        
        Response::view('admin/users', [
            'users' => $users
        ]);
    }

    /**
     * POST /admin/users/update-role - Atualiza role (apenas ADMIN)
     */
    public static function updateUserRole(): void
    {
        Auth::requireAdmin();
        
        $id = $_POST['id'] ?? '';
        $role = $_POST['role'] ?? '';
        
        if (empty($id) || !in_array($role, ['USER', 'MANAGER', 'ADMIN'])) {
            Response::jsonError('Dados inválidos.', 400);
        }
        
        // Não pode alterar o próprio role
        if ($id === Auth::id()) {
            Response::jsonError('Você não pode alterar seu próprio nível de acesso.', 400);
        }
        
        try {
            User::update($id, ['role' => $role]);
            Response::jsonSuccess('Nível de acesso atualizado!');
        } catch (\Exception $e) {
            Response::jsonError('Erro ao atualizar: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /admin/users/delete - Remove usuário (apenas ADMIN)
     */
    public static function deleteUser(): void
    {
        Auth::requireAdmin();
        
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            Response::jsonError('ID não especificado.', 400);
        }
        
        // Não pode deletar a si mesmo
        if ($id === Auth::id()) {
            Response::jsonError('Você não pode deletar sua própria conta.', 400);
        }
        
        try {
            User::delete($id);
            Response::jsonSuccess('Usuário removido com sucesso!');
        } catch (\Exception $e) {
            Response::jsonError('Erro ao remover usuário: ' . $e->getMessage(), 500);
        }
    }

    // ========== MÉTRICAS ==========

    /**
     * GET /admin/metrics - Visualiza métricas (apenas ADMIN)
     */
    public static function metrics(): void
    {
        Auth::requireAdmin();
        
        $period = (int) ($_GET['period'] ?? 7);
        
        if (!in_array($period, [7, 30, 90])) {
            $period = 7;
        }
        
        $summary = Metric::summary($period);
        $topApps = Metric::topApps($period, 10);
        $dailyOpens = Metric::dailyOpens($period);
        
        Response::view('admin/metrics', [
            'period' => $period,
            'summary' => $summary,
            'topApps' => $topApps,
            'dailyOpens' => $dailyOpens
        ]);
    }
}
