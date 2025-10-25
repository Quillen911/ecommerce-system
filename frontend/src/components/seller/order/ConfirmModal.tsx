import { FiCheck, FiX } from "react-icons/fi";

type ShipmentConfirmModalProps = {
  open: boolean;
  loading?: boolean;
  onConfirm: () => void;
  onCancel: () => void;
};

export default function ShipmentConfirmModal({
  open,
  loading = false,
  onConfirm,
  onCancel,
}: ShipmentConfirmModalProps) {
  if (!open) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
      <div className="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <h3 className="text-lg font-semibold text-gray-900">
          Siparişi kargoya verildi olarak işaretlemek istediğinize emin misiniz?
        </h3>
        <p className="mt-2 text-sm text-gray-600">
          Bu işlem, müşterinin siparişi için kargo bildirimi gönderir. Lütfen paketin gerçekten kargoya teslim edildiğinden emin olun.
        </p>

        <div className="mt-6 flex justify-end gap-3">
          <button
            type="button"
            onClick={onCancel}
            className="inline-flex items-center gap-2 rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 cursor-pointer"
          >
            <FiX className="h-4 w-4" />
            İptal Et
          </button>
          <button
            type="button"
            onClick={onConfirm}
            disabled={loading}
            className="inline-flex items-center gap-2 rounded-md bg-black px-4 py-2 text-sm font-semibold text-white transition hover:bg-opacity-90 disabled:cursor-not-allowed disabled:bg-opacity-60 cursor-pointer"
          >
            <FiCheck className="h-4 w-4" />
            {loading ? "İşleniyor…" : "Onayla"}
          </button>
        </div>
      </div>
    </div>
  );
}
