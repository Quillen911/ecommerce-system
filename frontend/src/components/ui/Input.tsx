import React from 'react'
import { InputProps } from '@/types/user'

export default function Input({
    label,
    placeholder,
    value,
    onChange,
    type = 'text',
    required = false, // sadece yıldız göstermek için
    disabled,
    error,
    autoComplete,
}: InputProps) {
    return (
        <div className="w-full">
            {label && (
                <label className="block text-sm font-medium text-gray-700 mb-1">
                    {label}
                    {required && <span className="text-red-500 ml-1">*</span>}
                </label>
            )}

            <input
                type={type}
                placeholder={placeholder}
                value={value}
                onChange={(e) => onChange?.(e.target.value)}
                disabled={disabled}
                autoComplete={autoComplete}
                className={`
                    w-full px-3 py-2 border rounded-md shadow-sm
                    focus:outline-none focus:ring-2 focus:ring-blue-500
                    placeholder:text-gray-400 text-gray-900
                    ${error ? 'border-red-500' : 'border-gray-300'}
                    ${disabled ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'}    
                `}
            />

            {error && (
                <p className="mt-1 text-sm text-red-600">{error}</p>
            )}
        </div>
    )
}
