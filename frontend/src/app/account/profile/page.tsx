'use client'
import { useEffect, useState } from 'react'
import { motion } from 'framer-motion'
import { useProfile, useUpdateProfile } from '@/hooks/useAuthQuery'
import LoadingState from '@/components/ui/LoadingState'

export default function ProfilePage() {
    const [mounted, setMounted] = useState(false)
    useEffect(() => setMounted(true), [])
    const { data: profile, isLoading, error } = useProfile()
    const updateProfileMutation = useUpdateProfile()
    
    const [isEditing, setIsEditing] = useState(false)
    const [formData, setFormData] = useState({
        first_name: '',
        last_name: '',
        username: '',
        email: '',
        phone: ''
    })

    const handleEdit = () => {
        if (profile?.user) {
            setFormData({
                first_name: profile.user.first_name || '',
                last_name: profile.user.last_name || '',
                username: profile.user.username || '',
                email: profile.user.email || '',
                phone: profile.user.phone || ''
            })
        }
        setIsEditing(true)
    }

    const handleSave = () => {
        updateProfileMutation.mutate(formData, {
            onSuccess: () => {
                setIsEditing(false)
            }
        })
    }

    const handleCancel = () => {
        setIsEditing(false)
    }

    const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value
        })
    }

    if (!mounted) {
        return null
    }

    if (isLoading) {
        return (
            <div className="flex items-center justify-center h-screen">
                <div className="text-center py-12 sm:py-16 px-4">
                    <p className="text-lg sm:text-xl font-semibold text-gray-900 mb-2 animate-pulse">Profil bilgileri yükleniyor...</p>
                </div>
            </div>
        )
    }

    if (error) {
        return (
            <div className="bg-red-50 border border-red-200 rounded-xl p-6">
                <div className="flex items-center space-x-2 text-red-700">
                    <span className="text-lg font-medium">Profil bilgileri yüklenirken bir hata oluştu</span>
                </div>
            </div>
        )
    }

    return (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
        >
            <div className="mb-8">
                <h1 className="text-3xl font-bold text-gray-900 mb-2">Profilim</h1>
                <div className="w-16 h-1 bg-black rounded-full"></div>
            </div>

            <div className="max-w-2xl">
                <div className="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <div className="flex items-center justify-between mb-6">
                        <h2 className="text-2xl font-bold text-gray-900">Kişisel Bilgiler</h2>
                        {!isEditing && (
                            <button
                                onClick={handleEdit}
                                className="bg-black text-white px-4 py-2 rounded-xl font-medium hover:bg-gray-800 transition-colors duration-200 flex items-center space-x-2"
                            >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Düzenle</span>
                            </button>
                        )}
                    </div>

                    {isEditing ? (
                        <div className="space-y-4">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">Ad</label>
                                    <input
                                        type="text"
                                        name="first_name"
                                        value={formData.first_name}
                                        onChange={handleInputChange}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">Soyad</label>
                                    <input
                                        type="text"
                                        name="last_name"
                                        value={formData.last_name}
                                        onChange={handleInputChange}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                                    />
                                </div>
                            </div>
                            
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">Kullanıcı Adı</label>
                                <input
                                    type="text"
                                    name="username"
                                    value={formData.username}
                                    disabled
                                    className="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                                />
                                <p className="text-xs text-gray-500 mt-1">Kullanıcı adı değiştirilemez</p>
                            </div>
                            
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">E-posta</label>
                                <input
                                    type="email"
                                    name="email"
                                    value={formData.email}
                                    disabled
                                    className="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                                />
                                <p className="text-xs text-gray-500 mt-1">E-posta adresi değiştirilemez</p>
                            </div>
                            
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                                <input
                                    type="tel"
                                    name="phone"
                                    value={formData.phone}
                                    onChange={handleInputChange}
                                    className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                                />
                            </div>

                            <div className="flex gap-3 pt-4">
                                <button
                                    onClick={handleSave}
                                    disabled={updateProfileMutation.isPending}
                                    className="bg-black text-white px-6 py-2 rounded-xl font-medium hover:bg-gray-800 transition-colors duration-200 disabled:opacity-50 flex items-center space-x-2"
                                >
                                    {updateProfileMutation.isPending ? (
                                        <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                    ) : (
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                        </svg>
                                    )}
                                    <span>Kaydet</span>
                                </button>
                                <button
                                    onClick={handleCancel}
                                    className="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors duration-200"
                                >
                                    İptal
                                </button>
                            </div>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-500 mb-1">Ad</label>
                                    <p className="text-lg text-gray-900">{profile?.user.first_name || '-'}</p>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-500 mb-1">Soyad</label>
                                    <p className="text-lg text-gray-900">{profile?.user.last_name || '-'}</p>
                                </div>
                            </div>
                            
                            <div>
                                <label className="block text-sm font-medium text-gray-500 mb-1">Kullanıcı Adı</label>
                                <p className="text-lg text-gray-900">{profile?.user.username || '-'}</p>
                            </div>
                            
                            <div>
                                <label className="block text-sm font-medium text-gray-500 mb-1">E-posta</label>
                                <p className="text-lg text-gray-900">{profile?.user.email || '-'}</p>
                            </div>
                            
                            <div>
                                <label className="block text-sm font-medium text-gray-500 mb-1">Telefon</label>
                                <p className="text-lg text-gray-900">{profile?.user.phone || '-'}</p>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </motion.div>
    )
}