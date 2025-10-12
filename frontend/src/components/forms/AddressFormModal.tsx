'use client'
import { motion, AnimatePresence } from 'framer-motion'
import AddressForm from './AddressForm'

interface AddressFormModalProps {
    isOpen: boolean
    onClose: () => void
    onSubmit: (data: any, options?: any) => void
    initialData?: any
    isLoading?: boolean
    title?: string
    submitText?: string
}

export default function AddressFormModal({
    isOpen,
    onClose,
    onSubmit,
    initialData,
    isLoading = false,
    title = "Adres Düzenle",
    submitText = "Güncelle"
}: AddressFormModalProps) {
    
    return (
        <AnimatePresence>
            {isOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                    {/* Backdrop */}
                    <motion.div 
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        className="absolute inset-0 bg-black bg-opacity-50"
                        onClick={onClose}
                    />
                    
                    {/* Modal */}
                    <motion.div 
                        initial={{ opacity: 0, scale: 0.95, y: 20 }}
                        animate={{ opacity: 1, scale: 1, y: 0 }}
                        exit={{ opacity: 0, scale: 0.95, y: 20 }}
                        transition={{ duration: 0.3, ease: "easeOut" }}
                        className="relative bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden shadow-2xl"
                        onClick={(e) => e.stopPropagation()}
                    >
                        {/* Header */}
                        <div className="flex items-center justify-between p-6 border-b border-gray-200">
                            <h3 className="text-2xl font-bold text-gray-900">{title}</h3>
                            <button 
                                onClick={onClose}
                                className="p-2 hover:bg-gray-100 rounded-full transition-colors duration-200"
                            >
                                <svg className="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        {/* Form Content */}
                        <div className="overflow-y-auto max-h-[calc(90vh-120px)]">
                            <div className="p-6">
                                
                                <AddressForm
                                    initialData={initialData}
                                    onSubmit={onSubmit}
                                    onCancel={onClose}
                                    isLoading={isLoading}
                                    submitText={submitText}
                                />
                                
                            </div>
                        </div>
                    </motion.div>
                </div>
            )}
        </AnimatePresence>
    )
}