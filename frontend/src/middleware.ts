import { NextResponse } from 'next/server'
import type { NextRequest } from 'next/server'

export function middleware(request: NextRequest) {
  const tokenCookie = request.cookies.get('token')?.value
  
  if (request.nextUrl.pathname.startsWith('/account')) {
    if (!tokenCookie) {
      return NextResponse.redirect(new URL('/login', request.url))
    }
  }
  
  return NextResponse.next()
}

export const config = {
  matcher: [
    '/account/:path*',

  ]
}