import { NextResponse } from 'next/server'
import type { NextRequest } from 'next/server'

export function middleware(request: NextRequest) {
  const tokenCookie = request.cookies.get('user_token')?.value
  const sellerTokenCookie = request.cookies.get('seller_token')?.value
  if (request.nextUrl.pathname.startsWith('/account')) {
    if (!tokenCookie) {
      return NextResponse.redirect(new URL('/login', request.url))
    }
  }

  if (request.nextUrl.pathname.startsWith('/seller')) {
    if (!sellerTokenCookie) {
      return NextResponse.redirect(new URL('/seller/login', request.url))
    }
  }
  
  return NextResponse.next()
}

export const config = {
  matcher: [
    '/account/:path*',
    '/seller/product/:path*',
    '/seller',
  ]
}