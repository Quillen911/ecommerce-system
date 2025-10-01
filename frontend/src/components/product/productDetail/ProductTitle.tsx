interface ProductTitleProps {
    title: string
}

export default function ProductTitle({ title }: ProductTitleProps) {
    return (
        <div className="product-title">
            <h1>{title}</h1>
        </div>
    )
}