export interface Category {
    id: number
    title: string
    slug: string
    parent_id: number | null
    parent?: ParentCategory | null   
    gender?: Gender | null 
    children?: Category[]       
  }
  
  export interface ParentCategory {
    id: number
    title: string
    slug: string
    parent_id: number | null
    parent?: Category | null 
    gender?: Gender | null 
    children?: Category[]       
  }

  export interface Gender {
    id: number
    title: string
    slug: string     
  }
    