<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Helpers\Security;
use App\Models\App;
use App\Models\Metric;
use App\Models\Pin;

/**
 * Controller de Apps
 */
class AppController
{
    /**
     * GET /internal?slug=... - Janela interna com iframe
     */
    public static function internal(): void
    {
        Auth::require();
        
        $slug = $_GET['slug'] ?? '';
        
        if (empty($slug)) {
            Response::notFound('App não especificado.');
        }
        
        $app = App::findBySlug($slug);
        
        if (!$app || !$app['active']) {
            Response::notFound('App não encontrado.');
        }
        
        // Verifica URL
        if (!Security::isValidUrl($app['url'])) {
            Response::serverError('URL do app inválida.');
        }
        
        // Verifica se deve abrir internamente
        $isInternal = $app['open_mode'] === 'INTERNAL' || 
                     ($app['open_mode'] === 'SMART' && !empty($app['allowlist_domains']));
        
        if (!$isInternal) {
            // Redireciona para externa
            Response::redirect($app['url']);
        }
        
        // Registra métrica
        Metric::logOpen($app['id'], Auth::id());
        
        // CSP dinâmica
        $frameSrc = Security::buildFrameSrc($app['allowlist_domains']);
        Security::sendCspForInternal($frameSrc);
        
        // Verifica se está pinned
        $isPinned = Pin::isPinned(Auth::id(), $app['id']);
        
        Response::view('internal', [
            'app' => $app,
            'isPinned' => $isPinned
        ]);
    }

    /**
     * GET /open?slug=... - Abre app e redireciona (nova aba)
     */
    public static function open(): void
    {
        Auth::require();
        
        $slug = $_GET['slug'] ?? '';
        
        if (empty($slug)) {
            Response::notFound('App não especificado.');
        }
        
        $app = App::findBySlug($slug);
        
        if (!$app || !$app['active']) {
            Response::notFound('App não encontrado.');
        }
        
        // Verifica URL
        if (!Security::isValidUrl($app['url'])) {
            Response::serverError('URL do app inválida.');
        }
        
        // Registra métrica
        Metric::logOpen($app['id'], Auth::id());
        
        // Redireciona
        Response::redirect($app['url']);
    }

    /**
     * POST /pins/add - Adiciona app aos favoritos
     */
    public static function addPin(): void
    {
        Auth::require();
        
        $appId = $_POST['app_id'] ?? '';
        
        if (empty($appId)) {
            Response::jsonError('App não especificado.', 400);
        }
        
        // Verifica se app existe
        $app = App::findById($appId);
        if (!$app) {
            Response::jsonError('App não encontrado.', 404);
        }
        
        // Adiciona pin
        $added = Pin::add(Auth::id(), $appId);
        
        if ($added) {
            Response::jsonSuccess('App adicionado aos favoritos.');
        } else {
            Response::jsonError('App já está nos favoritos.', 400);
        }
    }

    /**
     * POST /pins/remove - Remove app dos favoritos
     */
    public static function removePin(): void
    {
        Auth::require();
        
        $appId = $_POST['app_id'] ?? '';
        
        if (empty($appId)) {
            Response::jsonError('App não especificado.', 400);
        }
        
        // Remove pin
        $removed = Pin::remove(Auth::id(), $appId);
        
        if ($removed) {
            Response::jsonSuccess('App removido dos favoritos.');
        } else {
            Response::jsonError('App não está nos favoritos.', 400);
        }
    }

    /**
     * POST /pins/toggle - Alterna pin (adiciona ou remove)
     */
    public static function togglePin(): void
    {
        Auth::require();
        
        $appId = $_POST['app_id'] ?? '';
        
        if (empty($appId)) {
            Response::jsonError('App não especificado.', 400);
        }
        
        // Verifica se app existe
        $app = App::findById($appId);
        if (!$app) {
            Response::jsonError('App não encontrado.', 404);
        }
        
        // Verifica se está pinned
        if (Pin::isPinned(Auth::id(), $appId)) {
            Pin::remove(Auth::id(), $appId);
            Response::jsonSuccess('App removido dos favoritos.', ['pinned' => false]);
        } else {
            Pin::add(Auth::id(), $appId);
            Response::jsonSuccess('App adicionado aos favoritos.', ['pinned' => true]);
        }
    }
}
