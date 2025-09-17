import { User } from "./user"
export interface HeaderProps {
    className?: string
}

export interface UserMenuProps {
    user?: User
    isLoading?: boolean
    onLogout?: () => void
    className?: string
}

export interface CartButtonProps {
    itemCount?: number
    onClick?: () => void
    className?: string
}