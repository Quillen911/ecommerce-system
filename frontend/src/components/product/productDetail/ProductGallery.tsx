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
    <div className="flex flex-col md:flex-row gap-4">

      <div className="flex md:flex-col gap-2 mt-2 md:mt-0 overflow-x-auto md:overflow-y-auto order-2 md:order-none">
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

      <div className="flex-1 rounded-md overflow-hidden aspect-[4/5] bg-gray-50 cursor-pointer order-1 md:order-none">
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
          <div className="flex flex-col md:flex-row gap-6 max-w-6xl w-full h-full p-4">
            
            <div className="flex md:flex-col gap-3 overflow-x-auto md:overflow-y-auto order-2 md:order-none">
              {images.map((img, index) => (
                <button
                  key={img.id}
                  onClick={() => setCurrentIndex(index)}
                  className={`relative rounded-md overflow-hidden h-20 w-20 flex-shrink-0 flex items-center justify-center ${
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

            <div className="flex-1 flex items-center justify-center">
              <ZoomableImage src={images[currentIndex].image} />
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
