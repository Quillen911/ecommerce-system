export interface Category {
    id: number
    title: string
    slug: string
    parent_id: number
    children: Category[]
}