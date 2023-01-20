import { z } from 'zod'
import { isUndefined } from '~/utils/booleanUtil'
import { throwErr } from '~/utils/errorUtil'
import { objUtil } from '~/utils/objectUtil'
import { ModelBase } from '~/utils/type'
import { ModelBasic } from './basicModel'

export const SchemaDrivingSchoolModel = z.object({
  name: z.string(),
  address: z.string(),
  city: z.string(),
  zipCode: z.string().min(5).max(5),
  siretNum: z.string().min(14).max(14),
  phoneNumber: z.string().min(10).max(10),
  kbisUrl: z.string().url(),
})

export class ModelDrivingSchool extends ModelBasic {
  name = ''
  address = ''
  city = ''
  zipCode = ''
  siretNum = ''
  phoneNumber = ''
  kbisUrl = ''

  protected constructor(obj?: ModelBase<ModelDrivingSchool>) {
    if (isUndefined(obj)) {
      super()
      return
    } else if (!objUtil.isObject(obj)) {
      throwErr('obj is not an object')
    } else {
      super(obj)
      objUtil.hydrate(this, SchemaDrivingSchoolModel.parse(obj))
    }
  }
}
