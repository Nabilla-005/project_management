import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',  // sesuaikan base URL backend kamu
});

// Fungsi untuk mendapatkan token dari localStorage
function getToken() {
  return localStorage.getItem('token');
}

// Fungsi untuk menyimpan token ke localStorage
function setToken(token) {
  localStorage.setItem('token', token);
}

// Attach token ke tiap request kalau ada
api.interceptors.request.use(config => {
  const token = getToken();
  if (token) {
    config.headers['Authorization'] = `Bearer ${token}`;
  }
  return config;
});

// Flag untuk menahan multiple refresh calls
let isRefreshing = false;
let failedQueue = [];

const processQueue = (error, token = null) => {
  failedQueue.forEach(prom => {
    if (error) {
      prom.reject(error);
    } else {
      prom.resolve(token);
    }
  })

  failedQueue = [];
}

api.interceptors.response.use(
  response => response,
  async error => {
    const originalRequest = error.config;

    if (error.response?.status === 401 && !originalRequest._retry) {
      if (isRefreshing) {
        // Kalau sudah refresh, queue dulu promise ini
        return new Promise(function (resolve, reject) {
          failedQueue.push({ resolve, reject });
        }).then(token => {
          originalRequest.headers['Authorization'] = 'Bearer ' + token;
          return api(originalRequest);
        }).catch(err => {
          return Promise.reject(err);
        });
      }

      originalRequest._retry = true;
      isRefreshing = true;

      try {
        // Panggil API refresh token
        const rs = await axios.post('http://localhost:8000/api/auth/refresh', {}, {
          headers: {
            'Authorization': `Bearer ${getToken()}`,
          }
        });

        const newToken = rs.data.token;
        setToken(newToken);
        api.defaults.headers.common['Authorization'] = 'Bearer ' + newToken;
        originalRequest.headers['Authorization'] = 'Bearer ' + newToken;
        processQueue(null, newToken);
        return api(originalRequest);
      } catch (err) {
        processQueue(err, null);
        // kalau refresh gagal, hapus token & redirect login
        localStorage.removeItem('token');
        window.location.href = '/login'; // sesuaikan route login kamu
        return Promise.reject(err);
      } finally {
        isRefreshing = false;
      }
    }

    return Promise.reject(error);
  }
);

export default api;