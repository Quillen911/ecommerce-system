'use client'
import { useState } from 'react'
import { motion } from 'framer-motion'

interface AddressFormProps {
    initialData?: {
        title: string
        first_name: string
        last_name: string
        phone: string
        address_line_1: string
        address_line_2: string
        district: string
        city: string
        country: string
        postal_code: string
        is_default: boolean
        notes: string
    }
    onSubmit: (data: any, options?: any) => void
    onCancel: () => void
    isLoading?: boolean
    submitText?: string
}

export default function AddressForm({ 
    initialData, 
    onSubmit, 
    onCancel, 
    isLoading = false,
    submitText = "Kaydet"
}: AddressFormProps) {
    const [errors, setErrors] = useState<Record<string, string>>({})
    const [formData, setFormData] = useState({
        title: initialData?.title || '',
        first_name: initialData?.first_name || '',
        last_name: initialData?.last_name || '',
        phone: initialData?.phone || '',
        address_line_1: initialData?.address_line_1 || '',
        address_line_2: initialData?.address_line_2 || '',
        district: initialData?.district || '',
        city: initialData?.city || '',
        country: initialData?.country || '',
        postal_code: initialData?.postal_code || '',
        is_default: initialData?.is_default || false,
        notes: initialData?.notes || ''
    })



    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        setErrors({})
        
        onSubmit(formData, {
            onError: (error: any) => {
                console.log('Error:', error)
                if (error.response?.data?.errors) {
                    setErrors(error.response.data.errors)
                } else if (error.response?.data?.message) {
                    setErrors({ general: error.response.data.message })
                }
            }
        })
    }

    const ErrorMessage = ({ field }: { field: string }) => {
        if (!errors[field]) return null
        
        return (
            <motion.div
                initial={{ opacity: 0, y: -10 }}
                animate={{ opacity: 1, y: 0 }}
                className="mt-1 text-sm text-red-600 flex items-center space-x-1"
            >
                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" />
                </svg>
                <span>{errors[field]}</span>
            </motion.div>
        )
    }

    const GeneralError = () => {
        if (!errors.general) return null
        
        return (
            <motion.div
                initial={{ opacity: 0, y: -10 }}
                animate={{ opacity: 1, y: 0 }}
                className="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600 flex items-center space-x-2"
            >
                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" />
                </svg>
                <span>{errors.general}</span>
            </motion.div>
        )
    }
    const inputStyles = "w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all duration-200"
    const textareaStyles = "w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all duration-200 resize-none"

    return (
        <motion.form 
            onSubmit={handleSubmit} 
            className="space-y-6"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.3 }}
        >
            <GeneralError />
            
            {/* Adres Başlığı */}
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3, delay: 0.1 }}
            >
                <label className="block text-sm font-medium text-gray-700 mb-2">Adres Başlığı</label>
                <input
                    type="text"
                    placeholder="Örn: Ev, İş, Ofis..."
                    value={formData.title}
                    onChange={(e) => setFormData({...formData, title: e.target.value})}
                    className={`${inputStyles} ${errors.title ? 'border-red-500' : ''}`}
                    required
                />
                <ErrorMessage field="title" />
            </motion.div>

            {/* Ad Soyad */}
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3, delay: 0.2 }}
                className="grid grid-cols-1 md:grid-cols-2 gap-4"
            >
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Ad</label>
                    <input
                        type="text"
                        placeholder="Adınız"
                        value={formData.first_name}
                        onChange={(e) => setFormData({...formData, first_name: e.target.value})}
                        className={`${inputStyles} ${errors.first_name ? 'border-red-500' : ''}`}
                        required
                    />
                    <ErrorMessage field="first_name" />
                </div>
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Soyad</label>
                    <input
                        type="text"
                        placeholder="Soyadınız"
                        value={formData.last_name}
                        onChange={(e) => setFormData({...formData, last_name: e.target.value})}
                        className={`${inputStyles} ${errors.last_name ? 'border-red-500' : ''}`}
                        required
                    />
                    <ErrorMessage field="last_name" />
                </div>
            </motion.div>

            {/* Telefon */}
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3, delay: 0.3 }}
            >
                <label className="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                <input
                    type="tel"
                    placeholder="0555 555 55 55"
                    value={formData.phone}
                    onChange={(e) => setFormData({...formData, phone: e.target.value})}
                    className={`${inputStyles} ${errors.phone ? 'border-red-500' : ''}`}
                    required
                />
                <ErrorMessage field="phone" />
            </motion.div>

            {/* Adres Satırları */}
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3, delay: 0.4 }}
                className="space-y-4"
            >
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Adres Satırı 1</label>
                    <textarea
                        placeholder="Mahalle, cadde, sokak, kapı numarası..."
                        value={formData.address_line_1}
                        onChange={(e) => setFormData({...formData, address_line_1: e.target.value})}
                        className={`${textareaStyles} ${errors.address_line_1 ? 'border-red-500' : ''}`}
                        rows={3}
                        required
                    />
                    <ErrorMessage field="address_line_1" />
                </div>
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Adres Satırı 2 (Opsiyonel)</label>
                    <textarea
                        placeholder="Apartman, daire numarası, kat..."
                        value={formData.address_line_2}
                        onChange={(e) => setFormData({...formData, address_line_2: e.target.value})}
                        className={`${textareaStyles} ${errors.address_line_2 ? 'border-red-500' : ''}`}
                        rows={2}
                    />
                    <ErrorMessage field="address_line_2" />
                </div>
            </motion.div>

            {/* İlçe Şehir */}
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3, delay: 0.5 }}
                className="grid grid-cols-1 md:grid-cols-2 gap-4"
            >
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">İlçe</label>
                    <input
                        type="text"
                        placeholder="İlçe"
                        value={formData.district}
                        onChange={(e) => setFormData({...formData, district: e.target.value})}
                        className={`${inputStyles} ${errors.district ? 'border-red-500' : ''}`}
                        required
                    />
                    <ErrorMessage field="district" />
                </div>
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Şehir</label>
                    <input
                        type="text"
                        placeholder="Şehir"
                        value={formData.city}
                        onChange={(e) => setFormData({...formData, city: e.target.value})}
                        className={`${inputStyles} ${errors.city ? 'border-red-500' : ''}`}
                        required
                    />
                    <ErrorMessage field="city" />
                </div>
            </motion.div>

            {/* Ülke Posta Kodu */}
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3, delay: 0.6 }}
                className="grid grid-cols-1 md:grid-cols-2 gap-4"
            >
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Ülke</label>
                    <input
                        type="text"
                        placeholder="Türkiye"
                        value={formData.country}
                        onChange={(e) => setFormData({...formData, country: e.target.value})}
                        className={`${inputStyles} ${errors.country ? 'border-red-500' : ''}`}
                        required
                    />
                    <ErrorMessage field="country" />
                </div>
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Posta Kodu</label>
                    <input
                        type="text"
                        placeholder="34000"
                        value={formData.postal_code}
                        onChange={(e) => setFormData({...formData, postal_code: e.target.value})}
                        className={`${inputStyles} ${errors.postal_code ? 'border-red-500' : ''}`}
                    />
                    <ErrorMessage field="postal_code" />
                </div>
            </motion.div>

            {/* Varsayılan Checkbox */}
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3, delay: 0.7 }}
                className="flex items-center space-x-3 mt-6"
            >
                <input
                    type="checkbox"
                    id="is_default"
                    checked={formData.is_default}
                    onChange={(e) => setFormData({...formData, is_default: e.target.checked})}
                    className="w-5 h-5 text-black border-gray-300 rounded focus:ring-black focus:ring-2"
                />
                <label htmlFor="is_default" className="text-sm font-medium text-gray-700 cursor-pointer">
                    Bu adresi varsayılan adres yap
                </label>
            </motion.div>

            {/* Notlar */}
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3, delay: 0.8 }}
                className="mt-6"
            >
                <label className="block text-sm font-medium text-gray-700 mb-2">Notlar (Opsiyonel)</label>
                <textarea
                    placeholder="Adres için özel notlar..."
                    value={formData.notes}
                    onChange={(e) => setFormData({...formData, notes: e.target.value})}
                    className={`${textareaStyles} ${errors.notes ? 'border-red-500' : ''}`}
                    rows={3}
                />
                <ErrorMessage field="notes" />
            </motion.div>

            {/* Butonlar */}
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3, delay: 0.9 }}
                className="pt-6 space-y-4"
            >
                <button 
                    type="submit" 
                    disabled={isLoading}
                    className="w-full bg-black text-white py-3 px-6 rounded-xl font-medium hover:bg-gray-800 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2"
                >
                    {isLoading ? (
                        <>
                            <div className="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                            <span>Kaydediliyor...</span>
                        </>
                    ) : (
                        <span>{submitText}</span>
                    )}
                </button>
                <button 
                    type="button" 
                    onClick={onCancel}
                    className="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-xl font-medium hover:bg-gray-200 transition-all duration-200"
                >
                    İptal
                </button>
            </motion.div>
        </motion.form>
    )
}