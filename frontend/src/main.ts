import { setupLayouts } from 'virtual:generated-layouts'
import App from './App.vue'

import '@unocss/reset/tailwind.css'
import './styles/main.css'
import 'uno.css'
import { ViteCreateApp } from './createApp'
import generatedRoutes from '~pages'

const routes = setupLayouts(generatedRoutes)

export const createApp = ViteCreateApp(
  App,
  { routes, base: import.meta.env.BASE_URL },
  (ctx) => {
    // install all modules under `modules/`
    Object.values(import.meta.glob('./modules/*.ts', { eager: true })).forEach((item: any) =>
      item.install?.(ctx),
    )
  },
)

