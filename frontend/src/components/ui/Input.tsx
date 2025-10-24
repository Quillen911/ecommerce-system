import React from 'react'
import { InputProps } from '@/types/user'

export default function Input({
  label,
  placeholder,
  value,
  onChange,
  type = 'text',
  required = false,
  disabled,
  error,
  autoComplete,
  maxLength,
  pattern,
  inputMode,
}: InputProps) {
  return (
    <div className="w-full">
      {label && (
        <label className="mb-1 block text-sm font-medium text-gray-700">
          {label}
          {required && <span className="ml-1 text-red-500">*</span>}
        </label>
      )}

      <input
        type={type}
        placeholder={placeholder}
        value={value}
        onChange={(e) => onChange?.(e.target.value)}
        disabled={disabled}
        autoComplete={autoComplete}
        maxLength={maxLength}
        pattern={pattern}
        inputMode={inputMode}
        className={`w-full rounded-md border px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-gray-400 text-gray-900 ${
          error ? 'border-red-500' : 'border-gray-300'
        } ${disabled ? 'cursor-not-allowed bg-gray-100' : 'bg-white'}`}
      />

      {error && <p className="mt-1 text-sm text-red-600">{error}</p>}
    </div>
  )
}