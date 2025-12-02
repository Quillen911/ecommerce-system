import { FaTwitter, FaInstagram, FaGithub } from "react-icons/fa";

export default function FooterSocial() {
  return (
    <div className="text-center sm:text-left">
      <h3 className="font-semibold mb-3 text-lg text-black">Sosyal Medya</h3>
      <div className="flex justify-center sm:justify-start gap-5 text-2xl">
        <a
          href="https://twitter.com"
          target="_blank"
          rel="noopener noreferrer"
          className="hover:text-gray-400 transition-colors"
        >
          <FaTwitter />
        </a>
        <a
          href="https://instagram.com"
          target="_blank"
          rel="noopener noreferrer"
          className="hover:text-gray-400 transition-colors"
        >
          <FaInstagram />
        </a>
        <a
          href="https://github.com"
          target="_blank"
          rel="noopener noreferrer"
          className="hover:text-gray-400 transition-colors"
        >
          <FaGithub />
        </a>
      </div>
    </div>
  );
}
