/**
 * Service Worker DESABILITADO (para testes)
 * Renomeie para sw.js para usar esta versão
 */

// Desregistra qualquer service worker existente
self.addEventListener('install', () => {
  console.log('[SW] Service Worker desabilitado - apenas para testes');
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  console.log('[SW] Limpando caches...');
  event.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.map((key) => {
          console.log('[SW] Deletando cache:', key);
          return caches.delete(key);
        })
      );
    })
  );
  return self.clients.claim();
});

// Não intercepta requisições
self.addEventListener('fetch', () => {
  // Deixa passar direto para o network
  return;
});

console.log('[SW] Service Worker em modo desabilitado');
