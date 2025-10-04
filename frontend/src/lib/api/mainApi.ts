import axios from 'axios'
import { MainData } from '@/types/main'
const api = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
})

export const mainApi = {
    getMainData: () => api.get<{message: string, data: MainData}>('/main'),
}
