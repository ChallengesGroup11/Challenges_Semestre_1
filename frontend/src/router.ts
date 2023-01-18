import 'vue-router'

export {

}

declare module 'vue-router' {
  interface RouteMeta {
    // is optional
    isAdmin?: boolean
    // must be declared by every route
    requiresAuth?: boolean
    requiresNotAuth?: boolean
    roles?: string[]
  }
}
