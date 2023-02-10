import { AnyObject } from './type'

export namespace objUtil {
  export const isObject = (obj: unknown): obj is Object => obj?.constructor.name === 'Object'

  export const hydrate = (sourceToHydrate: AnyObject, objForHydrating: AnyObject): void => {
    for (const [key] of Object.entries(sourceToHydrate)) sourceToHydrate[key] = objForHydrating[key]
  }
}
