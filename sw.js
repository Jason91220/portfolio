const CACHE_NAME = 'jason-bedulho-portfolio-v1';
const urlsToCache = [
  '/',
  '/index.php',
  '/about.php',
  '/projects.php',
  '/skills.php',
  '/contact.php',
  '/assets/css/style.css',
  '/assets/css/dark-mode.css',
  '/assets/js/script.js',
  '/assets/js/dark-mode.js',
  '/assets/js/multi-lang.js',
  '/assets/img/logo/logo.webp',
  '/assets/docs/cv.pdf',
  '/manifest.json'
];

// Installation du service worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Cache ouvert');
        return cache.addAll(urlsToCache);
      })
  );
});

// Activation du service worker
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// Interception des requêtes
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Si la ressource est en cache, on la retourne
        if (response) {
          return response;
        }
        
        // Sinon, on fait la requête au réseau
        return fetch(event.request)
          .then(response => {
            // Si la réponse n'est pas valide, on retourne simplement la réponse
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }
            
            // On clone la réponse car elle ne peut être utilisée qu'une fois
            const responseToCache = response.clone();
            
            // On met en cache la nouvelle ressource
            caches.open(CACHE_NAME)
              .then(cache => {
                cache.put(event.request, responseToCache);
              });
              
            return response;
          });
      })
  );
});

// Gestion des notifications push
self.addEventListener('push', event => {
  const title = 'Jason Bedulho Portfolio';
  const options = {
    body: event.data.text(),
    icon: '/assets/img/icons/icon-192x192.png',
    badge: '/assets/img/icons/icon-72x72.png'
  };
  
  event.waitUntil(self.registration.showNotification(title, options));
});

// Gestion des clics sur les notifications
self.addEventListener('notificationclick', event => {
  event.notification.close();
  event.waitUntil(
    clients.openWindow('https://jasonbedulho.com')
  );
});
