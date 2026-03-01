import axios from 'axios'
import type { AxiosInstance } from 'axios'

export const url = 'http://127.0.0.1:8000';
// export const url = 'https://dashpomocat.devbyteztudios.com.br'

const api: AxiosInstance = axios.create({
  baseURL: `${url}/api`,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
})


export default api
