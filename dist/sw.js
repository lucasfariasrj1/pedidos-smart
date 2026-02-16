const CACHE_NAME = 'pedidos-smarthard-v1';
const OFFLINE_URL = '/login.php';

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll([
      '/manifest.webmanifest',
      '/css/adminlte.css',
      '/js/adminlte.js',
      '/assets/img/AdminLTELogo.png',
      '/assets/img/AdminLTEFullLogo.png',
      OFFLINE_URL,
    ]))
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => Promise.all(
      keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
    ))
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') {
    return;
  }

  event.respondWith(
    caches.match(event.request).then((cached) => {
      if (cached) {
        return cached;
      }

      return fetch(event.request)
        .then((response) => {
          const responseClone = response.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseClone));
          return response;
        })
        .catch(() => caches.match(OFFLINE_URL));
    })
  );
});
