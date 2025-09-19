import { Product } from "./main"


export interface SearchResponse {
    message: string
    data: {
        results: any
        total: number
        page: number
        size: number
        query: string
        products: Product[]
    }
}
