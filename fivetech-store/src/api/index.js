import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api/v1',  // hoặc import.meta.env.VITE_API_URL
  withCredentials: true,                    // BẮT BUỘC để cookie CSRF hoạt động
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
})

// Tự động lấy CSRF trước các request thay đổi trạng thái
api.interceptors.request.use(async (config) => {
  const methodsNeedCsrf = ['post', 'put', 'patch', 'delete']
  if (methodsNeedCsrf.includes(config.method)) {
    try {
      await axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true })  // set XSRF-TOKEN cookie
    } catch (csrfErr) {
      console.warn('CSRF fetch failed:', csrfErr)
    }
  }

  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
})

// Xử lý lỗi global (tùy chọn nâng cao)
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401 || error.response?.status === 419) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      // router.push('/login')  // hoặc thông báo session hết hạn
    }
    return Promise.reject(error)
  }
)

export default api