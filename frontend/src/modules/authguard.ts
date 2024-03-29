import type { UserModule } from "~/types"
import { hashUtil } from "~/utils/hashUtil"

type Roles = "ROLE_ADMIN" | "ROLE_USER" | "ROLE_DIRECTOR" | "ROLE_MONITOR"

interface TokenParsed {
  iat: string
  exp: string
  roles: Roles[]
  email: string
}

// // test
// const tokenParsedFake: TokenParsed = {
//   iat: '',
//   exp: '',
//   roles: ['ROLE_ADMIN', 'ROLE_STUDENT'],
//   email: '',
// }
export const install: UserModule = ({ isClient, router }) => {
  if (isClient) {
    router.beforeEach((to, from, next) => {
      if (to.path === "/") {
        next({
          path: "/auth",
        })
      }
      const token = localStorage.getItem("token")
      const tokenParsed = token ? (hashUtil.parseJwt(token) as TokenParsed) : null
      if (
        to.matched.some((record) => {
          return record.meta.requiresAuth
        })
      ) {
        if (!tokenParsed) {
          next({
            path: "/auth",
          })
        } else {
          if (
            to.matched.some((record) => {
              return record.meta.roles
            })
          ) {
            if (!tokenParsed.roles.includes(hashUtil.convertRoleFrontToDbRole(to.meta.roles))) {
              next({
                path: hashUtil.pathToRedirectByRole(tokenParsed.roles),
              })
            } else {
              if (from.name === "student-Add-CodeCertification-id" && to.name === "student-profil") {
                next()
              }
            }
          }
        }
      } else if (
        to.matched.some((record) => {
          return record.meta.requiresNotAuth
        })
      ) {
        if (tokenParsed) {
          next({
            path: hashUtil.pathToRedirectByRole(tokenParsed.roles),
          })
        } else {
          next()
        }
      }
      next()
    })
  }
}
