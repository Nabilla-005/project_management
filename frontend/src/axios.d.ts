declare module '@/axios' {
  import axios from 'axios';
  const api: typeof axios;
  export default api;
}