import { ReactNode } from 'react'

export default function SellerLayout({ 
    children 
}: { 
    children: ReactNode 
}) {
    
    return (
        <div>
            {children}
        </div>
    )
}
