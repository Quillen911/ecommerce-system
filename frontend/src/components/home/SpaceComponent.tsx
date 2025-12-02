export default function SpaceComponent() {
    const items = [
      {
        title: '256 Bit SSL ile güvende alışveriş',
        icon: (
          <svg
            xmlns="http://www.w3.org/2000/svg"
            className="w-23 h-23 stroke-[1.0]"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              d="M12 3.1 5 6v5c0 5.25 3.5 9 7 10 3.5-1 7-4.75 7-10V6l-7-2.9Z"
            />
            <path strokeLinecap="round" strokeLinejoin="round" d="m9.5 12 1.75 1.75L15 10" />
          </svg>
        ),
      },
      {
        title: '400 TL ve üzeri siparişlerde ücretsiz kargo',
        icon: (
          <svg
            xmlns="http://www.w3.org/2000/svg"
            className="w-23 h-23 stroke-[1.0]"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path strokeLinecap="round" strokeLinejoin="round" d="M3.5 8.5h9.5v7.5H3.5z" />
            <path strokeLinecap="round" strokeLinejoin="round" d="M13 11h3l2.5 3v2H13V11Z" />
            <circle cx="7" cy="17" r="1.2" />
            <circle cx="16" cy="17" r="1.2" />
          </svg>
        ),
      },
      {
        title: '14 gün içerisinde iade/değişim',
        icon: (
          <svg
            xmlns="http://www.w3.org/2000/svg"
            className="w-23 h-23 stroke-[1.0]"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <circle cx="12" cy="12" r="8.5" />
            <path strokeLinecap="round" strokeLinejoin="round" d="M12 7.5v9" />
            <path strokeLinecap="round" strokeLinejoin="round" d="M9.8 9.3h3c1.3 0 2.1.6 2.1 1.7 0 1.1-.8 1.7-2.1 1.7h-1.7" />
          </svg>
        ),
      },
    ]
  
    return (
      <div className="w-full bg-white py-15 sm:py-20">
        <div className="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-6 sm:gap-4 px-4">
          {items.map((item) => (
            <div key={item.title} className="flex flex-col items-center text-center gap-3 text-[var(--text, #0f172a)]">
              <div className="text-black">{item.icon}</div>
              <p className="text-base sm:text-lg font-medium">{item.title}</p>
            </div>
          ))}
        </div>
      </div>
    )
  }
  