import { z } from 'zod'
import { isUndefined } from '~/utils/booleanUtil'
import { throwErr } from '~/utils/errorUtil'
import { objUtil } from '~/utils/objectUtil'
import { ModelBase } from '~/utils/type'
import { ModelBasic } from './basicModel'
import { ModelPackage, SchemaModelPackage } from './packageModel'
import { ModelUser, SchemaModelUser } from './userModel'

export const SchemaModelPayment = z.object({
  user: SchemaModelUser,
  package: SchemaModelPackage,
})

export class ModelPayment extends ModelBasic {
  user!: ModelUser
  package!: ModelPackage

  protected constructor(obj?: ModelBase<ModelPayment>) {
    if (isUndefined(obj)) {
      super()
      return
    } else if (!objUtil.isObject(obj)) {
      throwErr('obj is not an object')
    } else {
      super(obj)
      objUtil.hydrate(this, SchemaModelPayment.parse(obj))
    }
  }
}
