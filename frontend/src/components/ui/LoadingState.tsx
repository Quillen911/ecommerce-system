'use client'

type LoadingStateProps = {
  label?: string;
  className?: string;
  fullScreen?: boolean;
};

export default function LoadingState({ label, className = '', fullScreen = false }: LoadingStateProps) {
  const base = 'flex flex-col items-center justify-center gap-4 rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-10 text-center';
  const sizeClass = fullScreen ? 'min-h-screen w-full' : 'min-h-[240px]';
  return (
    <div className={`${base} ${sizeClass} ${className}`}>
      <div className="h-10 w-10 animate-spin rounded-full border-2 border-gray-300 border-t-gray-800" />
      <p className="text-sm text-gray-600">{label ?? 'Yükleniyor...'}</p>
    </div>
  );
}

