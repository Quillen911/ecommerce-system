'use client'
import { useState } from 'react'
import Input from '@/components/ui/Input'
import { authApi } from '@/lib/api/authApi'

export default function LoginPage() {
    const [email, setEmail] = useState('')
    const [password, setPassword] = useState('')
    const [loading, setLoading] = useState(false)
    const [error, setError] = useState('')

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault()
        setLoading(true)
        setError('')

        try {
            const response = await authApi.login({ email, password })
            console.log('Backend response:', response)
            localStorage.setItem('token', response.data.data.token)

            window.location.href = '/'
            
            console.log('Giriş Başarılı', response.data)
        } catch (err: any) {
            setError(err.response?.data?.data?.message || 'Giriş Başarısız')
        } finally {
            setLoading(false)
        }
    }


    return (
        <form onSubmit={handleSubmit} className="max-w-md mx-auto mt-20 p-8 space-y-4 bg-white rounded-lg shadow-lg">
            <h1 className="text-2xl font-bold text-gray-800">Giriş Yap</h1>

            {error && (
                <div className='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded'>
                    {error}
                </div>
            )}
            
            <Input 
                label="E-posta"
                placeholder="E-posta adresinizi girin"
                value={email}
                type="email"
                onChange={setEmail}
                autoComplete="email"
                required
            />
            
            <Input 
                label="Şifre"
                placeholder="Şifrenizi girin"
                type="password"
                value={password}
                onChange={setPassword}
                autoComplete="current-password"
                required
            />

            <button 
                type="submit"
                disabled={loading}
                className='w-full bg-green-700 text-white py-2 px-4 rounded hover:bg-gray-800 mt-4 disabled:opacity-50'
            >
                {loading ? 'Giriş yapılıyor...' : 'Giriş Yap'}
            </button>
        </form>
    )
}