'use client'

import { useEffect, useRef, useState } from 'react'

type Source = { src: string; type: string }

interface ProVideoProps {
  sources: Source[]          // [{ src: '/videos/hero-1080.mp4', type: 'video/mp4' }, ...]
  poster?: string            // '/images/video-poster.jpg'
  captionsSrc?: string       // '/captions/tr.vtt'
  title: string              // “Ürün tanıtım videosu”
  autoPlay?: boolean         // default false
  loop?: boolean             // default true
  muted?: boolean            // default true
  className?: string         // extra tailwind/clsx
}

export default function ProVideo({
  sources,
  poster,
  captionsSrc,
  title,
  autoPlay = false,
  loop = true,
  muted = true,
  className = ''
}: ProVideoProps) {
  const videoRef = useRef<HTMLVideoElement | null>(null)
  const [isReady, setIsReady] = useState(false)
  const [loadVideo, setLoadVideo] = useState(false)

  // Lazy load with IntersectionObserver
  useEffect(() => {
    if (!videoRef.current || loadVideo) return
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries.some((e) => e.isIntersecting)) {
          setLoadVideo(true)
          observer.disconnect()
        }
      },
      { threshold: 0.35 }
    )
    observer.observe(videoRef.current)
    return () => observer.disconnect()
  }, [loadVideo])

  // Respect reduced motion
  const shouldAutoplay = autoPlay && typeof window !== 'undefined'
    ? window.matchMedia?.('(prefers-reduced-motion: reduce)').matches
      ? false
      : true
    : autoPlay

  return (
    <div className={`relative aspect-video overflow-hidden rounded-xl bg-black/80 ${className}`}>
      {!isReady && (
        <div className="absolute inset-0 flex items-center justify-center text-white/70 text-sm">
          Video yükleniyor…
        </div>
      )}

      <video
        ref={videoRef}
        className={`h-full w-full object-cover transition-opacity duration-500 ${isReady ? 'opacity-100' : 'opacity-0'}`}
        poster={poster}
        playsInline
        controls
        preload={loadVideo ? 'auto' : 'metadata'}
        muted={muted}
        loop={loop}
        autoPlay={shouldAutoplay && loadVideo}
        onLoadedData={() => setIsReady(true)}
        onError={() => setIsReady(true)}
        aria-label={title}
      >
        {loadVideo &&
          sources.map((s) => <source key={s.src} src={s.src} type={s.type} />)}
        {captionsSrc && <track kind="captions" src={captionsSrc} srcLang="tr" label="Türkçe" default />}
        Tarayıcınız video etiketini desteklemiyor.
      </video>
    </div>
  )
}
