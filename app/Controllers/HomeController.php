<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Models\App;
use App\Models\Category;
use App\Models\Pin;

/**
 * Controller da Home (Launcher)
 */
class HomeController
{
    /**
     * GET / - Launcher (Home)
     */
    public static function index(): void
    {
        Auth::require();
        
        $search = trim($_GET['search'] ?? '');
        $categoryFilter = $_GET['category'] ?? '';
        
        // Busca apps
        if (!empty($search)) {
            $apps = App::search($search);
        } elseif (!empty($categoryFilter)) {
            $apps = App::byCategory($categoryFilter);
        } else {
            $apps = App::allActive();
        }
        
        // Busca categorias
        $categories = Category::withAppCount(true);
        
        // Busca pins do usuário
        $pins = Pin::userPins(Auth::id());
        $pinnedIds = array_column($pins, 'app_id');
        
        // Marca apps que estão pinned
        foreach ($apps as &$app) {
            $app['is_pinned'] = in_array($app['id'], $pinnedIds);
        }
        
        // Ordena: pinned primeiro, depois por order e nome
        usort($apps, function($a, $b) {
            if ($a['is_pinned'] !== $b['is_pinned']) {
                return $b['is_pinned'] <=> $a['is_pinned'];
            }
            if ($a['order'] !== $b['order']) {
                return $a['order'] <=> $b['order'];
            }
            return strcasecmp($a['name'], $b['name']);
        });
        
        Response::view('home', [
            'apps' => $apps,
            'categories' => $categories,
            'search' => $search,
            'categoryFilter' => $categoryFilter
        ]);
    }
}
