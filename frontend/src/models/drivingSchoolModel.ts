import { z } from 'zod'
import { objUtil } from '~/utils/objectUtil'
import { Base } from '~/utils/type'
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

export class DrivingSchoolModel extends ModelBasic {
  name = ''
  address = ''
  city = ''
  zipCode = ''
  siretNum = ''
  phoneNumber = ''
  kbisUrl = ''

  protected constructor(obj?: Base<DrivingSchoolModel>) {
    if (!objUtil.isObject(obj)) return
    super(obj)
    objUtil.hydrate(this, SchemaDrivingSchool.parse(obj))
  }
}
