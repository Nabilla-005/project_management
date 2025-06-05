import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

(window as any).Pusher = Pusher;

const echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,          // sesuaikan .env frontend
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,  // sesuaikan .env frontend
  forceTLS: true,
  encrypted: true,
  // authEndpoint kalau perlu otentikasi token JWT
  auth: {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`, // ambil token JWT dari localStorage
    },
  },
});

export default echo;