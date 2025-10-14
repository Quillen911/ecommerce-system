import { Order } from "@/types/order";
export interface OrderListProps {
    order: Order
}
export default function OrderList({ order }: OrderListProps) {
    console.log(order)
    return (
        <div>
            <li key ={order.id}>
                <span>Sipariş Numarası:</span>{order.order_number}
                <span>Toplam Tutar:</span>{order.grand_total_cents/100}
            </li>
           
        </div>
    )
}
