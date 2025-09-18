'use client'
import { useState } from 'react'
import { useRouter } from 'next/navigation'
import { motion, AnimatePresence } from 'framer-motion'
import Input from '@/components/ui/Input'
import { useLogin, useRegister, useCsrf } from '@/hooks/useAuthQuery'

export default function LoginRegisterSplit() {
  const router = useRouter()
  const loginMutation = useLogin()
  const registerMutation = useRegister()
  const csrfMutation = useCsrf()

  const [isLogin, setIsLogin] = useState(true)

  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [passwordConfirmation, setPasswordConfirmation] = useState('')
  const [firstName, setFirstName] = useState('')
  const [lastName, setLastName] = useState('')
  const [username, setUsername] = useState('')

  const [formErrors, setFormErrors] = useState<{ [key: string]: string[] }>({})
  
  const loading = loginMutation.isPending || registerMutation.isPending || csrfMutation.isPending
  const error = isLogin ? loginMutation.error?.response?.data?.message : registerMutation.error?.response?.data?.message
  const fieldErrors = isLogin ? loginMutation.error?.response?.data?.errors : registerMutation.error?.response?.data?.errors

  const clearForm = () => {
    setEmail('')
    setPassword('')
    setPasswordConfirmation('')
    setFirstName('')
    setLastName('')
    setUsername('')
    setFormErrors({})
    loginMutation.reset()
    registerMutation.reset()
  }

  const handleModeSwitch = (newIsLogin: boolean) => {
    clearForm()
    setIsLogin(newIsLogin)
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    
    setFormErrors({})
    loginMutation.reset()
    registerMutation.reset()
    
    try {
      await csrfMutation.mutateAsync()
      
      if (isLogin) {
        await loginMutation.mutateAsync({ email, password })
        router.push('/')
      } else {
        await registerMutation.mutateAsync({ 
          firstName: firstName, 
          lastName: lastName, 
          username, 
          email, 
          password, 
          password_confirmation: passwordConfirmation 
        })
        router.push('/')
      }
    } catch (error: any) {
      if (error?.response?.data?.errors) {
        const errors = error.response.data.errors
        setFormErrors(errors)
      }
    }
  }

  return (
    <div className="min-h-screen grid grid-cols-1 md:grid-cols-2 bg-gray-100">
      {/* Sol taraf  */}
      <div className="flex items-center justify-center p-12">    
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
          className="w-full max-w-sm"
        > 
          <AnimatePresence mode="wait">
            {isLogin ? (
              <motion.div
                key="login"
                initial={{ opacity: 0, x: -50 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: 50 }}
                transition={{ duration: 0.4 }}
              >
                <h1 className="text-3xl font-bold mb-8">Giriş Yap</h1>
                {error && (
                  <div className="bg-red-50 text-red-700 border border-red-200 rounded-lg p-3 text-sm mb-3">
                    {error}
                  </div>
                )}
                <form onSubmit={handleSubmit} className="space-y-4">
                  <Input 
                    label="E-posta Adresi" 
                    placeholder="ornek@email.com" 
                    value={email} 
                    onChange={setEmail} 
                    type="email" 
                    autoComplete="email"
                    error={formErrors?.email?.[0] || fieldErrors?.email?.[0]} 
                  />
                  <Input 
                    label="Şifre" 
                    placeholder="********" 
                    value={password} 
                    onChange={setPassword} 
                    type="password" 
                    autoComplete="password"
                    error={formErrors?.password?.[0] || fieldErrors?.password?.[0]} 
                  />
                  <div className="flex items-center justify-between">
                    <label className="flex items-center space-x-2 text-sm text-gray-600">
                      <input type="checkbox" className="rounded" />
                      <span>Beni hatırla</span>
                    </label>
                    <button className="text-sm text-gray-500 hover:underline">Şifremi unuttum</button>
                  </div>
                  <button
                    type="submit"
                    disabled={loading}
                    className="w-full bg-black text-white py-3 px-4 rounded-lg font-medium hover:bg-gray-800 transition duration-200"
                  >
                    {loading ? 'Giriş yapılıyor...' : 'Giriş Yap'}
                  </button>
                </form>
                <p className="text-sm text-gray-500 mt-6 text-center">
                  Hesabın yok mu?{' '}
                  <button onClick={() => handleModeSwitch(false)} className="text-black font-semibold hover:underline">
                    Kayıt Ol
                  </button>
                </p>
              </motion.div>
            ) : (
              <motion.div
                key="register"
                initial={{ opacity: 0, x: 50 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: -50 }}
                transition={{ duration: 0.4 }}
              >
                <h1 className="text-3xl font-bold mb-8">Kayıt Ol</h1>
                <form onSubmit={handleSubmit} className="space-y-4">
                  <Input label="Ad" value={firstName} onChange={setFirstName} autoComplete="given-name" error={formErrors?.first_name?.[0] || fieldErrors?.first_name?.[0]} />
                  <Input label="Soyad" value={lastName} onChange={setLastName} autoComplete="family-name" error={formErrors?.last_name?.[0] || fieldErrors?.last_name?.[0]} />
                  <Input label="Kullanıcı Adı" value={username} onChange={setUsername} autoComplete="username" error={formErrors?.username?.[0] || fieldErrors?.username?.[0]} />
                  <Input label="E-posta Adresi" value={email} onChange={setEmail} type="email" autoComplete="email" error={formErrors?.email?.[0] || fieldErrors?.email?.[0]} />
                  <Input label="Şifre" value={password} onChange={setPassword} type="password"  autoComplete="new-password" error={formErrors?.password?.[0] || fieldErrors?.password?.[0]} />
                  <Input label="Şifre Tekrarı" value={passwordConfirmation} onChange={setPasswordConfirmation} type="password" autoComplete="new-password" error={formErrors?.password_confirmation?.[0] || fieldErrors?.password_confirmation?.[0]} />
                  <button
                    type="submit"
                    disabled={loading}
                    className="w-full bg-black text-white py-3 px-4 rounded-lg font-medium hover:bg-gray-800 transition duration-200"
                  >
                    Kayıt Ol
                  </button>
                </form>
                <p className="text-sm text-gray-500 mt-6 text-center">
                  Zaten bir hesabın var mı?{' '}
                  <button onClick={() => handleModeSwitch(true)} className="text-black font-semibold hover:underline">
                    Giriş Yap
                  </button>
                </p>
              </motion.div>
            )}
          </AnimatePresence>
        </motion.div>
      </div>

      {/* Sağ taraf  */}
      <div className="hidden md:flex items-center justify-center bg-gray-100">
        <motion.div
          initial={{ opacity: 0, x: 50 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.8 }}
          className="w-full h-screen bg-black rounded-l-3xl shadow-2xl p-16 text-white flex flex-col justify-center"
        >
          <h2 className="text-5xl font-extrabold mb-8">Omnia’ya Hoş Geldiniz</h2>
          <p className="text-lg text-gray-300 mb-10 max-w-lg">
            Modern e-ticaret platformunu yönetmek için ihtiyacın olan her şey burada. 
            Siparişlerini, ürünlerini ve müşterilerini tek panelden kontrol et.
          </p>
          <div className="bg-white/10 rounded-2xl p-6 backdrop-blur-xl shadow-lg max-w-md">
            <h3 className="text-2xl font-semibold mb-3">Neden Omnia?</h3>
            <p className="text-gray-200">
              Hızlı, güvenli ve ölçeklenebilir altyapı. Sen sadece işini büyütmeye odaklan.
            </p>
          </div>
        </motion.div>
      </div>
    </div>
  )
}
