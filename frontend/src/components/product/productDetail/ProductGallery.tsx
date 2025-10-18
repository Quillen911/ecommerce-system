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
    <div className="flex flex-col md:flex-row gap-4 w-full">
      {/* Thumbnail list */}
      <div className="flex md:flex-col gap-3 mt-2 md:mt-0 overflow-x-auto md:overflow-y-auto order-2 md:order-none shrink-0">
        {images.map((img, index) => (
          <button
            key={img.id}
            onMouseEnter={() => setCurrentIndex(index)}
            className={`relative rounded-md overflow-hidden h-20 w-20 flex items-center justify-center transition-opacity ${
              currentIndex === index ? "opacity-100" : "opacity-60"
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

      {/* Main image */}
      <div
        className="flex-1 rounded-md overflow-hidden aspect-[4/5] bg-gray-50 cursor-pointer order-1 md:order-none"
        onClick={() => setIsOpen(true)}
      >
        <img
          src={images[currentIndex].image}
          alt="Product"
          className="w-full h-full object-contain md:object-cover max-h-[600px] sm:max-h-[500px] md:max-h-[650px] transition-transform duration-300 hover:scale-[1.02]"
        />
      </div>

      {/* Modal full screen */}
      {isOpen && (
        <div className="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 p-4">
          <div className="flex flex-col md:flex-row gap-6 max-w-6xl w-full h-full md:h-auto">
            {/* Modal thumbnails */}
            <div className="flex md:flex-col gap-3 overflow-x-auto md:overflow-y-auto order-2 md:order-none">
              {images.map((img, index) => (
                <button
                  key={img.id}
                  onClick={() => setCurrentIndex(index)}
                  className={`relative rounded-md overflow-hidden h-20 w-20 flex-shrink-0 flex items-center justify-center transition-opacity ${
                    currentIndex === index ? "opacity-100" : "opacity-50"
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

            {/* Zoomable image */}
            <div className="flex-1 flex items-center justify-center max-h-[85vh]">
              <ZoomableImage src={images[currentIndex].image} />
            </div>
          </div>

          <button
            className="absolute top-5 right-6 text-white text-3xl font-bold hover:scale-110 transition-transform"
            onClick={() => setIsOpen(false)}
          >
            ✕
          </button>
        </div>
      )}
    </div>
  );
}
