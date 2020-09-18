;
//asignar un nombre y versión al cache
import {precacheAndRoute} from 'workbox-precaching';

precacheAndRoute([{"revision":"b3dcbaa7a64c567f50ddb21ae011725f","url":"__index__.html"},{"revision":"a493ba0aa0b8ec8068d786d7248bb92c","url":"browserconfig.xml"},{"revision":"099b86a399c0f1cea06aeea74fae16db","url":"css/app.css"},{"revision":"2fd89f1f0c35bba05f2cc31d9e406327","url":"css/login.css"},{"revision":"f0b83963878bd58ca45581efadc2389f","url":"css/style.css"},{"revision":"53750d3d51cc31fecf3544f215123da3","url":"css/styles.css"},{"revision":"41225cf33289bcb206859ac1a69dee7b","url":"favicon.ico"},{"revision":"57b8e15338fbdd9892ac89e9a330cd3c","url":"images/android-chrome-192x192_1.png"},{"revision":"4043adf242b90b89eeecc1f122453e3b","url":"images/android-chrome-192x192.png"},{"revision":"dcd88da3433ec89edf8651cae159b209","url":"images/android-chrome-256x256_1.png"},{"revision":"5166b86e4e586cb7021b6dfbd2b81154","url":"images/android-chrome-256x256.png"},{"revision":"a23b3308e4e89f7b5c8c2ccc2bdaa37c","url":"images/android-chrome-512x512_1.png"},{"revision":"19cf9f1fd3f87c1cadf21a15a75f9af6","url":"images/android-chrome-512x512.png"},{"revision":"8c5fd22189864222ea38ed44acf79038","url":"images/apple-touch-icon.png"},{"revision":"ada313d9fca7f29251790bf7714ad05d","url":"images/favicon-16x16.png"},{"revision":"df857515d9f4d76798c0e421b5ce32b6","url":"images/favicon-32x32.png"},{"revision":"e0b34d24db71156adcb327d07e4a9075","url":"images/logo-web.png"},{"revision":"e572f3e94959cc0197ba158b98a3d748","url":"images/logo.png"},{"revision":"99a47b25c93604026b7d8092aa453c7f","url":"images/logo.svg"},{"revision":"788059c2dbc576b32f7bc5f6d9e3b8f3","url":"images/mstile-150x150.png"},{"revision":"4acd09d6ba6f50782f360eb3eac6d86f","url":"images/safari-pinned-tab.svg"},{"revision":"0aa45905d51a1cfce9093b884ec41700","url":"images/splashscreens/ipad_splash.png"},{"revision":"d230b36c9b3def4770d836f0a04ae28f","url":"images/splashscreens/ipadpro1_splash.png"},{"revision":"6124355e3214d33b21ad420fb2454121","url":"images/splashscreens/ipadpro2_splash.png"},{"revision":"0f326c2a00a18441a2e3945ee7c84b6a","url":"images/splashscreens/ipadpro3_splash.png"},{"revision":"f556abe775421e48d3d206ca852c1aed","url":"images/splashscreens/iphone5_splash.png"},{"revision":"39f8cc24a6dfd40a3e2a454f9a02e3d6","url":"images/splashscreens/iphone6_splash.png"},{"revision":"4bca558ca223c4159c176621461ac742","url":"images/splashscreens/iphoneplus_splash.png"},{"revision":"994926e563403c9aaa76a274403e8cf9","url":"images/splashscreens/iphonex_splash.png"},{"revision":"933e8201f661db8298a07a391859ed3c","url":"images/splashscreens/iphonexr_splash.png"},{"revision":"087b38903e3a2008afd28ca7de3c961a","url":"images/splashscreens/iphonexsmax_splash.png"},{"revision":"b64af3868a227051a9d92e97f283b79b","url":"index.php"},{"revision":"6c367109ad313e3398f09949618eed6d","url":"js/app.js"},{"revision":"88025c5cf322e55625d7db80ed64b04b","url":"js/config.js"},{"revision":"a62797ac862cce76d00e53cb88683b7e","url":"js/home_get_ip.js"},{"revision":"c6495d73033f11b3a80b17a5f1e8e529","url":"js/home.js"},{"revision":"9e0c5e2a9cd71905488ddd335f6e54dc","url":"js/script.js"},{"revision":"2515d5669a879dadc2a8ef012af502dc","url":"manifest_mio.json"},{"revision":"735ab4f94fbcd57074377afca324c813","url":"robots.txt"},{"revision":"c00a51c14b5f1f958756ac1ac40d977c","url":"web.config"},{"revision":"c784a7d3c787022caad0c8c85d82aeb4","url":"workbox-config.js"}]);

const CACHE_NAME = 'v1_mars',
  urlsToCache = [
    '/js/script.js',
    'favicon.ico'
  ]

//durante la fase de instalación, generalmente se almacena en caché los activos estáticos
self.addEventListener('install', e => {
  console.log('Service worker instalado');
  e.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache)
          .then(() => self.skipWaiting())
      })
      .catch(err => console.log('Falló registro de cache', err))
  )
})

//una vez que se instala el SW, se activa y busca los recursos para hacer que funcione sin conexión
self.addEventListener('activate', e => {
  console.log('Service worker activado');
  const cacheWhitelist = [CACHE_NAME]

  e.waitUntil(
    caches.keys()
      .then(cacheNames => {
        return Promise.all(
          cacheNames.map(cacheName => {
            //Eliminamos lo que ya no se necesita en cache
            if (cacheWhitelist.indexOf(cacheName) === -1) {
              return caches.delete(cacheName)
            }
          })
        )
      })
      // Le indica al SW activar el cache actual
      .then(() => self.clients.claim())
  )
})

//cuando el navegador recupera una url
self.addEventListener('fetch', e => {
  //Responder ya sea con el objeto en caché o continuar y buscar la url real
  e.respondWith(
    caches.match(e.request)
      .then(res => {
        if (res) {
          //recuperar del cache
          return res
        }
        //recuperar de la petición a la url
        return fetch(e.request)
      })
  )
})
