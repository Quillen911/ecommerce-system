export interface Category {
  id: number
  title: string
  slug: string
  parent_id: number | null
  gender_id: number
  parent?: ParentCategory | null
  gender: Gender
  children?: Category[]
}

export interface ParentCategory {
  id: number
  title: string
  slug: string
  parent_id: number | null
  gender_id: number
  parent?: Category | null
  gender: Gender
  children?: Category[]
}

  export interface Gender {
    id: number
    title: string
    slug?: string     
  }
    