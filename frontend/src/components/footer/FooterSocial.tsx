import { FaTwitter, FaInstagram, FaGithub } from "react-icons/fa";

export default function FooterSocial() {
  return (
    <div>
      <h3 className="font-semibold mb-3">Sosyal Medya</h3>
      <div className="flex gap-4 text-xl">
        <a href="https://twitter.com" target="_blank" className="hover:text-gray-400"><FaTwitter /></a>
        <a href="https://instagram.com" target="_blank" className="hover:text-gray-400"><FaInstagram /></a>
        <a href="https://github.com" target="_blank" className="hover:text-gray-400"><FaGithub /></a>
      </div>
    </div>
  );
}
