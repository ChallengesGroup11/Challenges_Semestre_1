import { z } from 'zod'
import { objUtil } from '~/utils/objectUtil'
import { Base } from '~/utils/type'
import { ModelBasic } from './basicModel'

export const SchemaModelPackage = z.object({
  name: z.string(),
  description: z.string(),
  nbCreditNumber: z.number(),
  price: z.number(),
})

export class ModelPackage extends ModelBasic {
  name = ''
  description = ''
  nbCreditNumber = 0
  price = 0

  protected constructor(obj?: Base<ModelPackage>) {
    if (!objUtil.isObject(obj)) return
    super(obj)
    objUtil.hydrate(this, SchemaModelPackage.parse(obj))
  }
}
