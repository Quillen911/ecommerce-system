'use client'
import { useMe } from "@/hooks/useAuthQuery";
import { useOrder } from "@/hooks/useOrderQuery";
import OrderList from "@/components/order/OrderList";


export default function OrdersPage() {
    const {data: me} = useMe() 
    const { data: order, isLoading, error } = useOrder(me?.id)
    console.log(order)
    if(isLoading) return <div>Loading...</div>
    if(error) return <div>Error: {error.message}</div>
    return (
    <div>
        <h1>Siparişlerim</h1>
        {order ? (
            <OrderList order={order}/>
        ) : (
            <div>Sipariş bulunamadı</div>
        )}
    </div>
)
}