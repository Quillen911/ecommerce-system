"use client";

import Link from "next/link";
import { useMemo, useState } from "react";
import { useMainData } from "@/hooks/useMainQuery";
import type { Gender } from "@/types/seller/category";

type GenderColumn = Gender & { id: number };

interface Props {
  isMobile?: boolean;
}

export default function CategoryDropdown({ isMobile = false }: Props) {
  const { data, isError } = useMainData();
  const categories = data?.categories ?? [];
  const [active, setActive] = useState<string | null>(null);

  const genderColumns = useMemo(() => {
    const seen = new Set();
    const result: GenderColumn[] = [];
    categories.forEach((c) => {
      if (c.gender_id && c.gender?.title && !seen.has(c.gender_id)) {
        seen.add(c.gender_id);
        result.push({
          id: c.gender_id,
          title: c.gender.title,
        });
      }
    });
    return result;
  }, [categories]);

  if (isError && !categories.length)
    return (
      <div className="text-sm text-gray-400">
        Kategoriler yüklenemedi.
      </div>
    );

  const chunkIntoThree = (arr: typeof categories) => {
    const size = Math.ceil(arr.length / 3);
    return [arr.slice(0, size), arr.slice(size, 2 * size), arr.slice(2 * size)];
  };

  if (isMobile) {
    return (
      <div className="flex flex-col gap-5">
        {genderColumns.map((col) => (
          <div key={col.id}>
            <h3 className="font-semibold uppercase text-white">{col.title}</h3>
            <ul className="mt-2 space-y-2 pl-2">
              {categories
                .filter(
                  (c) =>
                    c.gender?.title === col.title &&
                    c.gender_id !== null
                )
                .map((cat) => (
                  <li key={cat.id}>
                    <Link
                      href={`/${cat.slug}`}
                      className="hover:underline text-gray-300"
                    >
                      {cat.title}
                    </Link>
                  </li>
                ))}
            </ul>
          </div>
        ))}
      </div>
    );
  }

  return (
    <div className="relative" onMouseLeave={() => setActive(null)}>
      <div className="flex justify-center gap-8 py-5">
        {genderColumns.map((col) => (
          <div key={col.id}>
            <button
              onMouseEnter={() => setActive(col.title)}
              className={`uppercase tracking-wide text-sm font-semibold transition-colors cursor-pointer ${
                active === col.title
                  ? "text-gray-300 border-b-2 border-white pb-1"
                  : "text-white hover:text-gray-300"
              }`}
            >
              {col.title}
            </button>
          </div>
        ))}
      </div>

      {active && (
        <div
          className="fixed left-0 top-[64px] w-screen bg-[#111] text-gray-200 z-50 border-t border-neutral-800 shadow-2xl animate-fadeSlideDown"
          onMouseEnter={() => setActive(active)}
        >
          <div className="max-w-[1400px] mx-auto px-[8vw] py-5">
            <div
              key={active}
              className="grid grid-cols-3 gap-12 animate-fadeSlideDown"
            >
              {chunkIntoThree(
                categories.filter(
                  (c) =>
                    c.gender?.title === active && c.gender_id !== null
                )
              ).map((col, i) => (
                <div key={i}>
                  <ul className="space-y-2">
                    {col.map((cat) => (
                      <li key={cat.id}>
                        <Link
                          href={`/${cat.slug}`}
                          className="hover:underline text-sm"
                        >
                          {cat.title}
                        </Link>
                      </li>
                    ))}
                  </ul>
                </div>
              ))}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
