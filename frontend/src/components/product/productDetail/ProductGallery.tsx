import { useState } from "react";
import ZoomableImage from "@/components/ui/ZoomableImage";

interface ProductGalleryProps {
  images: { id: number; image: string; is_primary: boolean; sort_order: number }[];
}

export default function ProductGallery({ images }: ProductGalleryProps) {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [isOpen, setIsOpen] = useState(false);

  if (!images || images.length === 0) {
    return <div>No images available</div>;
  }

  return (
    <div className="flex gap-4">

      <div className="flex flex-col gap-2 justify-start overflow-y-auto">
        {images.map((img, index) => (
          <button
            key={img.id}
            onMouseEnter={() => setCurrentIndex(index)}
            className={`relative rounded-md overflow-hidden h-20 w-20 flex items-center justify-center ${
              currentIndex === index 
              ? "opacity-100" 
              : "opacity-60"
            }`}
          >
            <div className="flex-1 overflow-hidden aspect-[4/5] bg-gray-50">
              <img
                src={img.image}
                alt={`thumb-${img.id}`}
                className="object-contain h-full w-full"
              />
            </div>
          </button>
        ))}
      </div>

      <div className="flex-1 rounded-md overflow-hidden aspect-[4/5] bg-gray-50 cursor-pointer">
        <img
          src={images[currentIndex].image}
          onClick={() => setIsOpen(true)}
          alt="Product"
          className="w-full h-full object-cover"
        />
      </div>

      {/* Modal */}
      {isOpen && (
        <div className="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50">
          <div className="flex gap-6 max-w-6xl w-full justify-start p-25">
            <div className="flex flex-col gap-3 overflow-y-auto">
              {images.map((img, index) => (
                <button
                  key={img.id}
                  onClick={() => setCurrentIndex(index)}
                  className={`relative rounded-md overflow-hidden h-20 w-20 flex items-center justify-center ${
                    currentIndex === index
                      ? "opacity-100"
                      : "opacity-50"
                  }`}
                >
                  <img
                    src={img.image}
                    alt={`thumb-modal-${img.id}`}
                    className="object-contain h-full w-full"
                  />
                </button>
              ))}
            </div>

            <div className="flex-1 flex items-center justify-center cursor-pointer">
              <ZoomableImage
                src={images[currentIndex].image}
              />
            </div>
          </div>

          <button
            className="absolute top-6 right-6 text-white text-3xl"
            onClick={() => setIsOpen(false)}
          >
            ✕
          </button>
        </div>
      )}
    </div>
  );
}
