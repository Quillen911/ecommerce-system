// frontend/src/app/auth/reset-password/page.tsx
"use client";

import { FormEvent, useMemo, useState } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { motion } from "framer-motion";
import Input from "@/components/ui/Input";
import { useResetPassword } from "@/hooks/useAuthQuery";

type FieldErrors = {
  email?: string;
  password?: string;
  password_confirmation?: string;
  general?: string;
};

const resolveToken = (params: URLSearchParams): string => {
  const explicit = params.get("token");
  if (explicit) return explicit;

  const keys = Array.from(params.keys());
  if (keys.length === 1 && !params.get(keys[0])) {
    return keys[0];
  }
  return "";
};

export default function ResetPasswordPage() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const resetPassword = useResetPassword();

  const token = useMemo(() => resolveToken(searchParams), [searchParams]);
  const defaultEmail = searchParams.get("email") ?? "";

  const [email, setEmail] = useState(defaultEmail);
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [errors, setErrors] = useState<FieldErrors>({});
  const [successMessage, setSuccessMessage] = useState<string | null>(null);

  const canSubmit = useMemo(
    () =>
      email.trim().length > 0 &&
      password.length >= 8 &&
      password === passwordConfirmation &&
      token.length > 0,
    [email, password, passwordConfirmation, token],
  );

  const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setErrors({});
    setSuccessMessage(null);

    resetPassword.mutate(
      {
        email,
        token,
        password,
        password_confirmation: passwordConfirmation,
      },
      {
        onSuccess: (data) => {
          setSuccessMessage(
            data?.message ?? "Şifreniz başarıyla güncellendi. Giriş sayfasına yönlendiriliyorsunuz…",
          );
          setTimeout(() => router.push("/login"), 1800);
        },
        onError: (error) => {
          const payload = error ?? {};
          const validationErrors = payload.errors ?? {};
          if (Object.keys(validationErrors).length > 0) {
            setErrors({
              email: validationErrors.email?.[0],
              password: validationErrors.password?.[0],
              password_confirmation: validationErrors.password_confirmation?.[0],
              general: validationErrors.token?.[0],
            });
          } else {
            setErrors({
              general:
                payload.message ??
                "Şifre sıfırlama işleminde beklenmeyen bir hata oluştu. Lütfen tekrar deneyin.",
            });
          }
        },
      },
    );
  };

  return (
    <div className="grid min-h-screen grid-cols-1 bg-gray-100 md:grid-cols-2">
      <div className="flex items-center justify-center p-12">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
          className="w-full max-w-sm"
        >
          <motion.div
            initial={{ opacity: 0, x: -50 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.4 }}
          >
            <h1 className="mb-2 text-3xl font-bold">Yeni Şifre Belirle</h1>
            <p className="mb-6 text-sm text-gray-500">
              Mailinizdeki bağlantı ile geldiniz. Yeni şifrenizi girerek hesabınıza tekrar erişebilirsiniz.
            </p>

            {errors.general && (
              <motion.div
                initial={{ opacity: 0, y: -12 }}
                animate={{ opacity: 1, y: 0 }}
                className="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700"
              >
                {errors.general}
              </motion.div>
            )}

            {successMessage && (
              <motion.div
                initial={{ opacity: 0, y: -12 }}
                animate={{ opacity: 1, y: 0 }}
                className="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700"
              >
                {successMessage}
              </motion.div>
            )}

            <form onSubmit={handleSubmit} className="space-y-4">
              <Input
                label="E-posta"
                placeholder="ornek@email.com"
                value={email}
                onChange={(value) => setEmail(value)}
                type="email"
                autoComplete="email"
                required
                disabled={resetPassword.isPending}
                error={errors.email}
              />

              <Input
                label="Yeni Şifre"
                placeholder="********"
                value={password}
                onChange={(value) => setPassword(value)}
                type="password"
                autoComplete="new-password"
                required
                disabled={resetPassword.isPending}
                error={errors.password}
              />

              <Input
                label="Yeni Şifre (Tekrar)"
                placeholder="********"
                value={passwordConfirmation}
                onChange={(value) => setPasswordConfirmation(value)}
                type="password"
                autoComplete="new-password"
                required
                disabled={resetPassword.isPending}
                error={errors.password_confirmation}
              />

              <button
                type="submit"
                disabled={!canSubmit || resetPassword.isPending}
                className="w-full cursor-pointer rounded-lg bg-black px-4 py-3 text-sm font-semibold text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:bg-gray-300" 
              >
                {resetPassword.isPending ? "Gönderiliyor…" : "Şifreyi Sıfırla"}
              </button>
            </form>

            <p className="mt-6 text-center text-sm text-gray-500">
              Linke yanlışlıkla mı tıkladınız?{" "}
              <button
                type="button"
                onClick={() => router.push("/login")}
                className="font-semibold text-black hover:underline cursor-pointer"
              >
                Giriş Yap
              </button>
            </p>
          </motion.div>
        </motion.div>
      </div>

      <div className="hidden h-full items-center justify-center bg-gray-100 md:flex">
        <motion.div
          initial={{ opacity: 0, x: 50 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.8 }}
          className="flex h-full w-full flex-col justify-center rounded-l-3xl bg-black p-16 text-white shadow-2xl"
        >
          <h2 className="mb-8 text-5xl font-extrabold">Omnia Güvenliği</h2>
          <p className="mb-10 max-w-lg text-lg text-gray-300">
            Hesabınızı korumak önceliğimiz. Güçlü bir şifre belirleyerek güvenle alışverişe devam edin.
          </p>
          <div className="max-w-md rounded-2xl bg-white/10 p-6 backdrop-blur-xl shadow-lg">
            <h3 className="mb-3 text-2xl font-semibold">Güçlü Parola İpuçları</h3>
            <p className="text-gray-200">
              En az 8 karakter, büyük-küçük harf ve rakam içeren kombinasyonlar kullanın. Şifrenizi kimseyle paylaşmayın.
            </p>
          </div>
        </motion.div>
      </div>
    </div>
  );
}
