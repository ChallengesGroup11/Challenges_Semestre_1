import { z } from 'zod'
import { isUndefined } from '~/utils/booleanUtil'
import { throwErr } from '~/utils/errorUtil'
import { objUtil } from '~/utils/objectUtil'
import { ModelBase } from '~/utils/type'
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

  protected constructor(obj?: ModelBase<ModelPackage>) {
    if (isUndefined(obj)) {
      super()
      return
    } else if (!objUtil.isObject(obj)) {
      throwErr('obj is not an object')
    } else {
      super(obj)
      objUtil.hydrate(this, SchemaModelPackage.parse(obj))
    }
  }
}
