"use client"

import { useEffect, useState } from "react";
import { useForm } from 'react-hook-form';
import ConfirmDialog from "@/components/ui/ConfirmDialog";
import { toast } from "sonner";
import { OrderItem } from "@/types/order";
import { SellerRefundItemRequest } from "@/types/seller/sellerOrder";
import { useOrderRefund } from "@/hooks/seller/useOrderQuery";

type RefundModalProps = {
    open: boolean;
    orderItem: OrderItem |null;
    onClose: () => void;
    onSuccess?: () => void;
}
type ApiFieldErrors = Record<string, string>
type AxiosErrorPayload = {
  errors?: Record<string, string[]>;
};
export default function RefundModal({open, orderItem, onClose, onSuccess}:RefundModalProps ) {
    const [apiErrors, setApiErrors] = useState<ApiFieldErrors>({});
    const { register, handleSubmit, reset, formState: {errors} } = useForm<SellerRefundItemRequest> ({
        defaultValues: {
            reason: '',
            quantity:1,
        }
    })

    const refundMutation = useOrderRefund();

    useEffect(() => {
        if(open && orderItem) {
            reset({
                reason: '',
                quantity: orderItem.quantity - (orderItem.refunded_quantity ?? 0) > 0
                        ? orderItem.quantity - (orderItem.refunded_quantity ?? 0)
                        : 1,
            });
            setApiErrors({});
        }
    }, [open, orderItem, reset])

    const extractApiErrors = (error: unknown): ApiFieldErrors => {
        if (error && typeof error === 'object' && 'response' in error) {
            const axiosError = error as {
            response?: { data?: AxiosErrorPayload };
            };

            const payload = axiosError.response?.data?.errors ?? {};
            const mapped: ApiFieldErrors = {};

            Object.entries(payload).forEach(([key, messages]) => {
            mapped[key] = messages.join(' ');
            });

            return mapped;
        }

        return {};
    };

    const onSubmit = handleSubmit(async (values) =>{
        if(!orderItem) return;

        await refundMutation.mutateAsync(
            {
                orderId:orderItem.id,
                payload: values,
            },
            {
                onSuccess: () => {
                    toast.success('İade işlemi Başarılı');
                    setApiErrors({});
                    onClose();
                    onSuccess?.();
                },
                onError: (error: unknown) => {
                    const fieldErrors = extractApiErrors(error);
                    setApiErrors(fieldErrors);
                },
            },
        );
    });

    return (
        <ConfirmDialog
            open={open}
            title="Ürünü İade Et"
            description={orderItem ? `${orderItem.product.title} için iade talebi.`: 'Ürün Bulunamadı'}
            confirmLabel={refundMutation.isPending ? 'İade Ediliyor...': 'İade Et'}
            cancelLabel="İptal"
            loading={refundMutation.isPending}
            onCancel={onClose}
            onConfirm={onSubmit}
        >
            <form className="mt-4 space-y-3">
                <div>
                    <label className="block text-xs font-medium text-gray-600">İade Nedeni</label>
                    <textarea 
                        className={`w-full rounded-xl border px-3 py-2 text-sm focus:outline-none ${
                                errors.reason || apiErrors.reason
                                    ? 'border-red-400 focus:border-red-500 focus:ring-red-200'
                                    : 'border-gray-200 focus:border-gray-900 focus:ring-gray-200'
                                }`}
                                placeholder="İade Gerekçesini Yazınız"
                                {...register('reason', {
                                    required: 'iade nedei zorunludur',
                                    minLength: { value: 5, message: 'En az 5 karakter girin.' },
                                })}
                    />
                    {errors.reason && <p className="mt-1 text-xs text-red-500">{errors.reason.message}</p>}
                    {apiErrors.reason && <p className="mt-1 text-xs text-red-500">{apiErrors.reason}</p>}

                </div>

                <div>
                    <label className="block text-xs font-medium text-gray-600">İade Edilecek Adet</label>
                    <input
                        type="number"
                        min={1}
                        max={orderItem?.quantity}
                        className={`w-full rounded-xl border px-3 py-2 text-sm focus:outline-none ${
                        errors.quantity || apiErrors.quantity
                            ? 'border-red-400 focus:border-red-500 focus:ring-red-200'
                            : 'border-gray-200 focus:border-gray-900 focus:ring-gray-200'
                        }`}
                        {...register('quantity', {
                        required: 'Adet zorunludur.',
                        valueAsNumber: true,
                        min: { value: 1, message: 'En az 1 adet seçin.' },
                        })}
                    />
                    {errors.quantity && <p className="mt-1 text-xs text-red-500">{errors.quantity.message}</p>}
                    {apiErrors.quantity && <p className="mt-1 text-xs text-red-500">{apiErrors.quantity}</p>}
                    </div>
            </form>
        </ConfirmDialog>
    )
}
