'use client'

import { useEffect, useMemo, useState } from 'react'
import { toast } from 'sonner'
import { useRouter } from 'next/navigation'
import { useMySeller } from '@/hooks/seller/useSellerAuthQuery'
import { useProductList, useDeleteProduct } from '@/hooks/seller/useProductQuery'
import ProductListHeader from '@/components/seller/product/list/ProductListHeader'
import ProductGrid from '@/components/seller/product/list/ProductGrid'
import { ProductDrawer } from '@/components/seller/product/ProductDrawer'
import ConfirmDialog from '@/components/ui/ConfirmDialog'
import LoadingState from '@/components/ui/LoadingState'
import EmptyState from '@/components/ui/EmptyState'
import type { Product } from '@/types/seller/product'

export default function ProductPage() {
  const router = useRouter()
  const { data: me } = useMySeller()
  const sellerId = me?.id
  const { data, isLoading } = useProductList(sellerId)
  const products = useMemo(() => data?.data ?? [], [data])

  const deleteMutation = useDeleteProduct(sellerId)
  const [drawerState, setDrawerState] = useState<{ open: boolean; product?: Product }>({ open: false })
  const [confirmState, setConfirmState] = useState<{ open: boolean; product?: Product }>({ open: false })
  const [hydrated, setHydrated] = useState(false)

  useEffect(() => {
    setHydrated(true)
  }, [])

  if (!hydrated) {
    return (
      <div className="space-y-4 sm:space-y-6 px-3 sm:px-6">
        <ProductListHeader total={0} onCreate={() => {}} disabled />
        <LoadingState label="Yükleniyor…" />
      </div>
    )
  }

  const handleOpenCreate = () => setDrawerState({ open: true })
  const handleOpenEdit = (product: Product) => setDrawerState({ open: true, product })
  const handleCloseDrawer = () => setDrawerState({ open: false })
  const handleView = (productId: number) => router.push(`/seller/product/${productId}`)
  const handleAskDelete = (product: Product) => setConfirmState({ open: true, product })
  const handleCancelDelete = () => setConfirmState({ open: false })

  const handleConfirmDelete = async () => {
    if (!confirmState.product) return
    await deleteMutation.mutateAsync(confirmState.product.id, {
      onSuccess: () => toast.success('Ürün silindi'),
      onError: (error: unknown) =>
        toast.error(error instanceof Error ? error.message : 'Silme başarısız'),
    })
    setConfirmState({ open: false })
  }

  if (isLoading) {
    return (
      <LoadingState label="Yükleniyor…" />
    )
  }

  if (!products.length) {
    return (
      <div className="px-3 sm:px-6 space-y-6">
        <ProductListHeader total={0} onCreate={handleOpenCreate} disabled={!sellerId} />
        <EmptyState
          title="Henüz ürün yok"
          description="Yeni ürün ekleyerek vitrinini oluştur."
          actionLabel="Yeni Ürün Ekle"
          onAction={handleOpenCreate}
          actionDisabled={!sellerId}
        />
        <ProductDrawer
          isOpen={drawerState.open}
          product={drawerState.product}
          sellerId={sellerId}
          onClose={handleCloseDrawer}
        />
      </div>
    )
  }

  return (
    <div className="p-3 sm:p-6 md:p-10 space-y-6 sm:space-y-10">
      <ProductListHeader total={products.length} onCreate={handleOpenCreate} disabled={!sellerId} />

      <div className="overflow-x-auto">
        <ProductGrid
          products={products}
          onView={handleView}
          onEdit={handleOpenEdit}
          onDelete={handleAskDelete}
        />
      </div>

      <ProductDrawer
        isOpen={drawerState.open}
        product={drawerState.product}
        sellerId={sellerId}
        onClose={handleCloseDrawer}
      />

      <ConfirmDialog
        open={confirmState.open}
        title="Ürünü sil"
        description="Bu ürünü silmek istediğine emin misin? Bu işlem geri alınamaz."
        confirmLabel="Sil"
        cancelLabel="İptal"
        loading={deleteMutation.isPending}
        onConfirm={handleConfirmDelete}
        onCancel={handleCancelDelete}
      />
    </div>
  )
}
