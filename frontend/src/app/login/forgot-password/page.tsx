"use client";

import { FormEvent, useState } from "react";
import { useRouter } from "next/navigation";
import { motion } from "framer-motion";
import Input from "@/components/ui/Input";
import { useForgotPassword } from "@/hooks/useAuthQuery";

type FieldErrors = {
  email?: string;
  general?: string;
};

export default function ForgotPasswordPage() {
  const router = useRouter();
  const { mutateAsync: forgotPassword, isPending } = useForgotPassword();

  const [email, setEmail] = useState("");
  const [successMessage, setSuccessMessage] = useState<string | null>(null);
  const [errors, setErrors] = useState<FieldErrors>({});

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setErrors({});
    setSuccessMessage(null);

    try {
      const { message } = await forgotPassword({ email });
      setSuccessMessage(message ?? "Parola sıfırlama bağlantısı gönderildi.");
      setEmail("");
    } catch (error: any) {
      const response = error?.response;
      if (response?.status === 422 && response.data?.errors) {
        const validationErrors = response.data.errors as Record<string, string[]>;
        setErrors({
          email: validationErrors.email?.[0],
        });
      } else {
        setErrors({
          general:
            response?.data?.message ??
            "Parola sıfırlama isteği sırasında bir hata oluştu. Lütfen tekrar deneyin.",
        });
      }
    }
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
            <h1 className="mb-2 text-3xl font-bold">Şifremi Unuttum</h1>
            <p className="mb-6 text-sm text-gray-500">
              E-posta adresinizi girin, size parola sıfırlama bağlantısı gönderelim.
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
                className="mb-4 rounded-lg border border-emerald-600 bg-emerald-50 p-3 text-sm text-gray-700"
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
                disabled={isPending}
                error={errors.email}
              />

              <button
                type="submit"
                disabled={email.trim().length === 0 || isPending}
                className="w-full rounded-lg bg-black px-4 py-3 text-sm font-semibold text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:bg-gray-300 cursor-pointer"
              >
                {isPending ? "Gönderiliyor…" : "Parola Sıfırla"}
              </button>
            </form>

            <p className="mt-6 text-center text-sm text-gray-500">
              Parolanızı hatırladınız mı?{" "}
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
          <h2 className="mb-8 text-5xl font-extrabold">Omnia’ya Hoş Geldiniz</h2>
          <p className="mb-10 max-w-lg text-lg text-gray-300">
            Güvenli ve hızlı işlem deneyimi için hesabınıza tekrar erişmenize yardımcı olalım.
          </p>
          <div className="max-w-md rounded-2xl bg-white/10 p-6 backdrop-blur-xl shadow-lg">
            <h3 className="mb-3 text-2xl font-semibold">Şifrenizi mi unuttunuz?</h3>
            <p className="text-gray-200">
              Endişelenmeyin, e-posta adresinizi girerek birkaç dakika içinde yeniden erişim sağlayabilirsiniz.
            </p>
          </div>
        </motion.div>
      </div>
    </div>
  );
}
