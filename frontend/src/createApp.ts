import type { Component } from 'vue'
import { createApp as createClientApp, createSSRApp } from 'vue'
import { createMemoryHistory, createRouter, createWebHistory } from 'vue-router'
import type { HeadClient } from '@vueuse/head'
import { createHead } from '@vueuse/head'
import type { RouterOptions, ViteVuaAppClientOptions, ViteVueContext } from './types'

export function ViteCreateApp(
  App: Component,
  routerOptions: RouterOptions,
  fn?: (context: ViteVueContext) => Promise<void> | void,
  options: ViteVuaAppClientOptions = {},
) {
  const { useHead = true, rootContainer = '#app' } = options
  const isClient = typeof window !== 'undefined'

  async function createApp(client = false, routePath?: string) {
    const app = client ? createClientApp(App) : createSSRApp(App)

    let head: HeadClient | undefined

    if (useHead) {
      head = createHead()
      app.use(head)
    }

    const router = createRouter({
      history: client ? createWebHistory(routerOptions.base) : createMemoryHistory(routerOptions.base),
      ...routerOptions,
    })

    const { routes } = routerOptions

    const appRenderCallbacks: Function[] = []
    const onSSRAppRendered = client ? () => {} : (cb: Function) => appRenderCallbacks.push(cb)
    const triggerOnSSRAppRendered = () => {
      return Promise.all(appRenderCallbacks.map((cb) => cb()))
    }
    const context: ViteVueContext = {
      app,
      head,
      isClient,
      router,
      routes,
      onSSRAppRendered,
      triggerOnSSRAppRendered,
      initialState: {},
      routePath,
    }

    await fn?.(context)

    app.use(router)

    if (!client) {
      const route = context.routePath ?? '/'
      router.push(route)

      await router.isReady()
      context.initialState = (router.currentRoute.value.meta.state as Record<string, any>) || {}
    }

    const initialState = context.initialState

    return {
      ...context,
      initialState,
    } as ViteVueContext
  }

  if (isClient) {
    ;(async () => {
      const { app, router } = await createApp(true)
      // wait until page component is fetched before mounting
      await router.isReady()
      app.mount(rootContainer, true)
    })()
  }

  return createApp
}
