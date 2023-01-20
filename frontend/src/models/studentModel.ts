import { z } from 'zod'
import { objUtil } from '~/utils/objectUtil'
import { User } from './user'

export const SchemaModelStudent = z.object({
  nbHourDone: z.number(),
  codeCertificationUrl: z.string(),
  cniUrl: z.string(),
})

export class ModelStudent extends User {
  nbHourDone = 0
  codeCertificationUrl = ''
  cniUrl = ''

  protected constructor(obj?: ModelStudent) {
    if (!objUtil.isObject(obj)) return
    super(obj)
    objUtil.hydrate(this, SchemaModelStudent.parse(obj))
  }
}
