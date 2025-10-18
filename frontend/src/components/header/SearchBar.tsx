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
    const handleEnter = (e: React.KeyboardEvent<HTMLInputElement>) => {
      if (e.key === 'Enter') {
        handleSearch()
      }
    }

return (
  <div className="flex items-center p-2 w-full">
    <div className="flex items-center justify-between w-full">
      {/* Sol kısım: input + search */}
      <div className="flex items-center gap-2 flex-grow justify-start">
        
        {/* relative kapsayıcı */}
        <div className="relative">
          {/* ikon (sola sabitlenmiş) */}
          <svg
            className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 cursor-pointer"
            onClick={handleSearch}
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

          {/* input */}
          <input
            type="text"
            placeholder="Search"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            onKeyDown={handleEnter}
            className="w-64 pl-10 pr-3 py-2 rounded-xl focus:outline-none bg-black text-white"
          />
        </div>
      </div>
    </div>
  </div>
);

}
