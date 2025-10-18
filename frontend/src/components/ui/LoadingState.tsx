'use client';

import { useEffect, useState } from 'react';

type LoadingStateProps = {
  label?: string;
};

export default function LoadingState({ label }: LoadingStateProps) {
  const [hydrated, setHydrated] = useState(false);

  useEffect(() => {
    setHydrated(true);
  }, []);

  return (
    <div className="flex min-h-[240px] flex-col items-center justify-center gap-4 rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-10 text-center">
      {hydrated ? (
        <>
          <div className="h-10 w-10 animate-spin rounded-full border-2 border-gray-300 border-t-gray-800" />
          <p className="text-sm text-gray-600">{label ?? 'Yükleniyor...'}</p>
        </>
      ) : (
        <h2 className="text-xl font-semibold text-gray-900">
          {label ?? 'Yükleniyor...'}
        </h2>
      )}
    </div>
  );
}
