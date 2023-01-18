import { type UserModule } from '~/types'

export const install: UserModule = ({ router }) => {
  router.isReady().then(async () => {
    const { registerSW } = await import('virtual:pwa-register')
    registerSW({ immediate: true })
  })
}
