import { z } from 'zod'
import { objUtil } from '~/utils/objectUtil'
import { Base } from '~/utils/type'
import { ModelBasic } from './basicModel'
import { Package, SchemaModelPackage } from './packageModel'
import { UserSchema, User } from './user'

export const SchemaModelPayment = z.object({
  user: UserSchema,
  package: SchemaModelPackage,
})

export class ModelPayment extends ModelBasic {
  user!: User
  package!: Package

  protected constructor(obj?: Base<ModelPayment>) {
    if (!objUtil.isObject(obj)) return
    super(obj)
    objUtil.hydrate(this, SchemaModelPayment.parse(obj))
  }
}
