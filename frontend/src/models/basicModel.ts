import { z } from 'zod'
import { objUtil } from '~/utils/objectUtil'
import type { InterfaceBasicModel, ModelBase } from '~/utils/type'

const SchemaModelBasic = z.object({
  id: z.number(),
  createdAt: z.date(),
  updatedAt: z.date(),
})

export class ModelBasic implements InterfaceBasicModel {
  id = 0
  createdAt = new Date()
  updatedAt = new Date()

  protected constructor(obj?: ModelBase<ModelBasic>) {
    if (!objUtil.isObject(obj)) return
    objUtil.hydrate(this, SchemaModelBasic.parse(obj))
  }

  static make<T>(obj?: ModelBase<any>): T | undefined {
    if (!obj) return undefined
    try {
      return new this(obj) as T
    } catch (e) {
      return undefined
    }
  }
}
