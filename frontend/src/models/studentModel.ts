import { z } from 'zod'
import { isUndefined } from '~/utils/booleanUtil'
import { throwErr } from '~/utils/errorUtil'
import { objUtil } from '~/utils/objectUtil'
import { ModelUser } from './userModel'

export const SchemaModelStudent = z.object({
  nbHourDone: z.number(),
  codeCertificationUrl: z.string(),
  cniUrl: z.string(),
})

export class ModelStudent extends ModelUser {
  nbHourDone = 0
  codeCertificationUrl = ''
  cniUrl = ''

  protected constructor(obj?: ModelStudent) {
    if (isUndefined(obj)) {
      super()
      return
    } else if (!objUtil.isObject(obj)) {
      throwErr('obj is not an object')
    } else {
      super(obj)
      objUtil.hydrate(this, SchemaModelStudent.parse(obj))
    }
  }
}
