import { useState } from "react";
import { useSearchQuery } from "@/hooks/useSearchQuery"
import { useRouter } from "next/navigation"

export default function SearchBox() {
    const router = useRouter()
    const [query, setQuery] = useState('')
    const [searchQuery, setSearchQuery] = useState('')
    const [isOpen, setIsOpen] = useState(false);
    const { data, isLoading, error } = useSearchQuery(searchQuery, undefined, undefined, 1, 12)

    
    const handleSearch = () => {
      if (query.trim()) {
        router.push(`/search?q=${encodeURIComponent(query)}`)
        setIsOpen(false)
      }
    }

  return (
    <div className="flex items-center p-2 w-full">
      {isOpen ? (
        <div className="flex items-center justify-between w-full">
          {/* Sol kısım: input + search */}
          <div className="flex items-center gap-2 flex-grow justify-start">
            <input
              type="text"
              placeholder="Search"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
              className="w-64 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-200"
            />
            <button
              onClick={handleSearch}
              className="w-5 h-5 hover:text-black transition-colors"
            >
              <svg
                className="w-5 h-5 text-gray-500 hover:text-black transition-colors cursor-pointer"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                />
              </svg>
            </button>
          </div>

          {/* Sağda sabit X */}
          <button onClick={() => setIsOpen(false)} className="ml-4">
            <svg
              className="w-5 h-5 text-gray-500 hover:text-black transition-colors cursor-pointer"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      ) : (
        <button onClick={() => setIsOpen(true)}>
          <svg
            className="w-5 h-5 text-gray-500 hover:text-black transition-colors cursor-pointer"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth={2}
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
            />
          </svg>
        </button>
      )}
    </div>
  );
}
