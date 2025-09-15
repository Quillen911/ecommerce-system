'use client'
import { useEffect, useState } from "react";
import { authApi } from '@/lib/api/authApi'

export default function Home() {
  const [username, setUsername] = useState('')
  const [loading, setLoading] = useState(true)

  const handleLogout = () => {
    authApi.logout()
      .then((response) => {
        console.log(response)
        localStorage.removeItem('token')
        window.location.href = '/login'
      })
      .catch((error) => {
        console.error('Çıkış yapılamadı', error)
      })
  }
  useEffect(() => {
    const token = localStorage.getItem('token');
    if (token) {
      (authApi.me() as Promise<any>)
        .then((response) => {
          setUsername(response.data.data.username)
        })
        .catch((error) => {
          console.error('Kullanıcı bilgileri alınamadı', error)
        })
        .finally(() => {
          setLoading(false)
        })
    } else {
      setLoading(false)
    }
  }, []);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <p>Yükleniyor...</p>
      </div>
    )
  }

  return (
    <div className="min-h-screen flex flex-col items-center justify-center">
      <h2 className="text-2xl font-bold">Hoş Geldiniz, {username}</h2>
      <div className="mt-4">
        <button onClick={handleLogout} className="bg-red-500 text-white px-4 py-2 rounded">Çıkış Yap</button>
      </div>
    </div>
    
  );
}
