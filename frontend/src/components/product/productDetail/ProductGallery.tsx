import { useState } from "react";

interface ProductGalleryProps {
  images: { id: number; image: string; is_primary: boolean; sort_order: number }[];
}

export default function ProductGallery({ images }: ProductGalleryProps) {
  if (!images || images.length === 0) {
    return <div>No images available</div>;
  }

  const [currentIndex, setCurrentIndex] = useState(0)
  const handleMouseMove = (id: number) => {
    const index = images.findIndex((img) => img.id === Number(id))
    setCurrentIndex(index);
  };

  return (
    <div className="flex flex-col gap-4">
      {/* Ana görsel */}
      <div className="border rounded-md overflow-hidden flex items-center justify-center h-96 bg-gray-50">
        <img
          src={images[currentIndex].image}
          onMouseMove={() => handleMouseMove(images[currentIndex].id)}
          alt="Product"
          className="object-contain max-h-full"
        />
      </div>

      {/* Thumbnail galerisi */}
      <div className="flex gap-2 justify-center">
        {images.map((img) => (
          <button
            key={img.id}
            onMouseMove={(e) => handleMouseMove(img.id)}
            className={`border rounded-md overflow-hidden h-20 w-20 flex items-center justify-center ${
              currentIndex === img.id ? "ring-2 ring-black" : ""
            }`}
          >
            <img
              src={img.image}
              alt={`thumb-${img.id}`}
              className="object-contain h-full w-full"
            />
          </button>
        ))}
      </div>
    </div>
  );
}
