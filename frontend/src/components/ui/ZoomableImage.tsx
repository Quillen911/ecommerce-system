import { useState } from "react";

export default function ZoomableImage({ src }: { src: string }) {
  const [isZoomed, setIsZoomed] = useState(false);
  const [position, setPosition] = useState({ x: 0, y: 0 });

  const handleMouseMove = (e: React.MouseEvent<HTMLDivElement>) => {
    const { left, top, width, height } = e.currentTarget.getBoundingClientRect();
    const x = ((e.pageX - left) / width) * 100;
    const y = ((e.pageY - top) / height) * 100;
    setPosition({ x, y });
  };

  return (
    <div
      className="relative w-[800px] h-[1000px] overflow-hidden rounded-lg shadow-lg"
      onMouseEnter={() => setIsZoomed(true)}
      onMouseLeave={() => setIsZoomed(false)}
      onMouseMove={handleMouseMove}
    >
      <img
        src={src}
        alt="Zoomable"
        className={`w-full h-full object-contain transition-transform duration-200 ${
          isZoomed ? "scale-300" : "scale-100"
        }`}
        style={{
          transformOrigin: `${position.x}% ${position.y}%`
        }}
      />
    </div>
  );
}
