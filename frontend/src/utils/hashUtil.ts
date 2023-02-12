export namespace hashUtil {
  export function parseJwt(token: string) {
    const base64Url = token.split('.')[1]
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/')
    const jsonPayload = decodeURIComponent(
      window
        .atob(base64)
        .split('')
        .map((c) => '%' + `00${c.charCodeAt(0).toString(16)}`.slice(-2))
        .join(''),
    )
    return JSON.parse(jsonPayload)
  }

  export function convertRoleFrontToDbRole(role: string) {
    switch (role) {
      case 'admin':
        return 'ROLE_ADMIN'
      case 'user':
        return 'ROLE_USER'
      case 'director':
        return 'ROLE_DIRECTOR'
      case 'monitor':
        return 'ROLE_MONITOR'
      default:
        return ''
    }
  }

  export function pathToRedirectByRole(roles: string[]) {
    if (roles.includes('ROLE_ADMIN')) {
      return '/admin'
    }
    if (roles.includes('ROLE_DIRECTOR')) {
      return '/director'
    }
    if (roles.includes('ROLE_MONITOR')) {
      return '/monitor'
    }
    if (roles.includes('ROLE_USER')) {
      return '/student'
    }
    return '/'
  }
}
