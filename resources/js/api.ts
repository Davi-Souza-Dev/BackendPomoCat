import axios from 'axios'
import type { AxiosInstance } from 'axios'

// export const url = 'https://dash.bakerfast.com.br';
export const url = ''

const api: AxiosInstance = axios.create({
  baseURL: `${url}`,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
})

export default api
