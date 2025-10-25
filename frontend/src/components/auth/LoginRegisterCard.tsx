"use client"

import { useMemo, useState, type FormEvent } from "react"
import { useRouter } from "next/navigation"
import { AnimatePresence, motion } from "framer-motion"

import Input from "@/components/ui/Input"
import { useCsrf, useLogin, useRegister } from "@/hooks/useAuthQuery"

type ErrorBag = Record<string, string[]>

const stripCombinedSuffix = (value: string) =>
  value.replace(/\s*\(and\s+\d+\s+more\s+errors?\)\s*$/i, "")

const normalizeMessages = (value: unknown): string[] => {
  if (!value) return []
  const list = Array.isArray(value) ? value : [value]
  return list
    .map((item) => (typeof item === "string" ? stripCombinedSuffix(item.trim()) : ""))
    .filter(Boolean)
}

const parseApiError = (payload: unknown): { general: string[]; fields: ErrorBag } => {
  if (!payload || typeof payload !== "object") {
    return { general: [], fields: {} }
  }

  const general = new Set<string>()
  const fields: ErrorBag = {}
  const data = payload as Record<string, unknown>

  if (typeof data.error === "string" && data.error.trim()) {
    general.add(stripCombinedSuffix(data.error.trim()))
  }
  if (typeof data.message === "string" && data.message.trim()) {
    general.add(stripCombinedSuffix(data.message.trim()))
  }

  if (data.errors && typeof data.errors === "object") {
    Object.entries(data.errors as Record<string, unknown>).forEach(([key, value]) => {
      const messages = normalizeMessages(value)
      if (messages.length) {
        fields[key] = messages
      }
    })
  }

  return {
    general: Array.from(general),
    fields,
  }
}

const mergeErrorBags = (primary: ErrorBag, secondary: ErrorBag): ErrorBag => {
  const merged: ErrorBag = {}

  const append = (key: string, messages: string[]) => {
    if (!messages || messages.length === 0) return
    if (!merged[key]) {
      merged[key] = []
    }
    messages.forEach((message) => {
      if (message && !merged[key].includes(message)) {
        merged[key].push(message)
      }
    })
  }

  Object.entries(primary).forEach(([key, messages]) => append(key, messages))
  Object.entries(secondary).forEach(([key, messages]) => append(key, messages))

  return merged
}

export default function LoginRegisterSplit() {
  const router = useRouter()
  const loginMutation = useLogin()
  const registerMutation = useRegister()
  const csrfMutation = useCsrf()

  const [isLogin, setIsLogin] = useState(true)

  const [email, setEmail] = useState("")
  const [password, setPassword] = useState("")
  const [passwordConfirmation, setPasswordConfirmation] = useState("")
  const [firstName, setFirstName] = useState("")
  const [lastName, setLastName] = useState("")
  const [username, setUsername] = useState("")

  const [manualFieldErrors, setManualFieldErrors] = useState<ErrorBag>({})
  const [manualGeneralErrors, setManualGeneralErrors] = useState<string[]>([])

  const activeMutationError = isLogin ? loginMutation.error : registerMutation.error
  const activePayload = (activeMutationError as any)?.response?.data ?? activeMutationError
  const parsedMutationError = useMemo(() => parseApiError(activePayload), [activePayload])

  const combinedFieldErrors = useMemo(
    () => mergeErrorBags(parsedMutationError.fields, manualFieldErrors),
    [parsedMutationError.fields, manualFieldErrors],
  )

  const combinedGeneralErrors = useMemo(() => {
    const generalBag = new Set<string>([
      ...parsedMutationError.general,
      ...manualGeneralErrors,
    ])
    return Array.from(generalBag)
  }, [parsedMutationError.general, manualGeneralErrors])

  if (combinedFieldErrors.general) {
    delete combinedFieldErrors.general
  }

  const fieldError = (field: string) => combinedFieldErrors[field]?.[0] ?? ""

  const loadingLogin = loginMutation.isPending || csrfMutation.isPending
  const loadingRegister = registerMutation.isPending || csrfMutation.isPending

  const clearForm = () => {
    setEmail("")
    setPassword("")
    setPasswordConfirmation("")
    setFirstName("")
    setLastName("")
    setUsername("")
    setManualFieldErrors({})
    setManualGeneralErrors([])
    loginMutation.reset()
    registerMutation.reset()
  }

  const handleModeSwitch = (nextMode: boolean) => {
    clearForm()
    setIsLogin(nextMode)
  }

  const handleError = (error: unknown) => {
    const parsed = parseApiError(
      (error as any)?.response?.data ?? (error as Record<string, unknown> | null),
    )
    setManualFieldErrors(parsed.fields)
    setManualGeneralErrors(parsed.general.length ? parsed.general : ["Beklenmeyen bir hata oluştu."])
  }

  const handleSubmit = async (event: FormEvent) => {
    event.preventDefault()

    setManualFieldErrors({})
    setManualGeneralErrors([])
    loginMutation.reset()
    registerMutation.reset()

    try {
      await csrfMutation.mutateAsync()

      if (isLogin) {
        await loginMutation.mutateAsync({ email, password })
        router.push("/")
      } else {
        await registerMutation.mutateAsync({
          first_name: firstName,
          last_name: lastName,
          username,
          email,
          password,
          password_confirmation: passwordConfirmation,
        })
        router.push("/")
      }
    } catch (error) {
      handleError(error)
    }
  }

  return (
    <div className="grid min-h-screen grid-cols-1 bg-gray-100 md:grid-cols-2">
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
                <h1 className="mb-8 text-3xl font-bold">Giriş Yap</h1>

                <AnimatePresence initial={false}>
                  {combinedGeneralErrors.length > 0 && (
                    <motion.div
                      key="login-errors"
                      initial={{ opacity: 0, y: -12 }}
                      animate={{ opacity: 1, y: 0 }}
                      exit={{ opacity: 0, y: -12 }}
                      className="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700"
                    >
                      <ul className="space-y-1">
                        {combinedGeneralErrors.map((message, index) => (
                          <li key={index}>{message}</li>
                        ))}
                      </ul>
                    </motion.div>
                  )}
                </AnimatePresence>

                <form onSubmit={handleSubmit} className="space-y-4">
                  <Input
                    label="E-posta Adresi"
                    placeholder="ornek@email.com"
                    value={email}
                    onChange={setEmail}
                    type="email"
                    autoComplete="email"
                    error={fieldError("email")}
                  />
                  <Input
                    label="Şifre"
                    placeholder="********"
                    value={password}
                    onChange={setPassword}
                    type="password"
                    autoComplete="current-password"
                    error={fieldError("password")}
                  />
                  <div className="flex items-center justify-end">
                    <button type="button" className="text-sm text-gray-500 hover:underline cursor-pointer">
                      Şifremi unuttum
                    </button>
                  </div>
                  <button
                    type="submit"
                    disabled={loadingLogin}
                    className="w-full rounded-lg bg-black px-4 py-3 font-medium text-white transition duration-200 hover:bg-gray-800 disabled:opacity-70 cursor-pointer"
                  >
                    {loadingLogin ? "Giriş yapılıyor..." : "Giriş Yap"}
                  </button>
                </form>

                <p className="mt-6 text-center text-sm text-gray-500">
                  Hesabın yok mu?{" "}
                  <button
                    onClick={() => handleModeSwitch(false)}
                    disabled={loadingRegister}
                    className="font-semibold text-black hover:underline cursor-pointer"
                  >
                    {loadingRegister ? "Kayıt oluyor..." : "Kayıt Ol"}
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
                <h1 className="mb-8 text-3xl font-bold">Kayıt Ol</h1>

                <AnimatePresence initial={false}>
                  {combinedGeneralErrors.length > 0 && (
                    <motion.div
                      key="register-errors"
                      initial={{ opacity: 0, y: -12 }}
                      animate={{ opacity: 1, y: 0 }}
                      exit={{ opacity: 0, y: -12 }}
                      className="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700"
                    >
                      <ul className="space-y-1">
                        {combinedGeneralErrors.map((message, index) => (
                          <li key={index}>{message}</li>
                        ))}
                      </ul>
                    </motion.div>
                  )}
                </AnimatePresence>

                <form onSubmit={handleSubmit} className="space-y-4">
                  <Input
                    label="Ad"
                    value={firstName}
                    onChange={setFirstName}
                    autoComplete="given-name"
                    error={fieldError("first_name")}
                  />
                  <Input
                    label="Soyad"
                    value={lastName}
                    onChange={setLastName}
                    autoComplete="family-name"
                    error={fieldError("last_name")}
                  />
                  <Input
                    label="Kullanıcı Adı"
                    value={username}
                    onChange={setUsername}
                    autoComplete="username"
                    error={fieldError("username")}
                  />
                  <Input
                    label="E-posta Adresi"
                    value={email}
                    onChange={setEmail}
                    type="email"
                    autoComplete="email"
                    error={fieldError("email")}
                  />
                  <Input
                    label="Şifre"
                    value={password}
                    onChange={setPassword}
                    type="password"
                    autoComplete="new-password"
                    error={fieldError("password")}
                  />
                  <Input
                    label="Şifre Tekrarı"
                    value={passwordConfirmation}
                    onChange={setPasswordConfirmation}
                    type="password"
                    autoComplete="new-password"
                    error={fieldError("password_confirmation")}
                  />
                  <button
                    type="submit"
                    disabled={loadingRegister}
                    className="w-full rounded-lg bg-black px-4 py-3 font-medium text-white transition duration-200 hover:bg-gray-800 disabled:opacity-70 cursor-pointer"
                  >
                    {loadingRegister ? "Kayıt yapılıyor..." : "Kayıt Ol"}
                  </button>
                </form>

                <p className="mt-6 text-center text-sm text-gray-500">
                  Zaten bir hesabın var mı?{" "}
                  <button
                    onClick={() => handleModeSwitch(true)}
                    className="font-semibold text-black hover:underline cursor-pointer"
                  >
                    Giriş Yap
                  </button>
                </p>
              </motion.div>
            )}
          </AnimatePresence>
        </motion.div>
      </div>

      <div className="hidden h-full items-center justify-center bg-gray-100 md:flex">
        <motion.div
          initial={{ opacity: 0, x: 50 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.8 }}
          className="flex h-full w-full flex-col justify-center rounded-l-3xl bg-black p-16 text-white shadow-2xl"
        >
          <h2 className="mb-8 text-5xl font-extrabold">Omnia’ya Hoş Geldiniz</h2>
          <p className="mb-10 max-w-lg text-lg text-gray-300">
            Modern e-ticaret platformunu yönetmek için ihtiyacın olan her şey burada. Siparişlerini,
            ürünlerini ve müşterilerini tek panelden kontrol et.
          </p>
          <div className="max-w-md rounded-2xl bg-white/10 p-6 backdrop-blur-xl shadow-lg">
            <h3 className="mb-3 text-2xl font-semibold">Neden Omnia?</h3>
            <p className="text-gray-200">
              Hızlı, güvenli ve ölçeklenebilir altyapı. Sen sadece işini büyütmeye odaklan.
            </p>
          </div>
        </motion.div>
      </div>
    </div>
  )
}
