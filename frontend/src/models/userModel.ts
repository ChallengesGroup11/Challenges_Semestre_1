import { ModelBasic } from './basicModel'
import { z } from 'zod'
import { objUtil } from '../utils/objectUtil'
import type { ModelBase } from '~/utils/type'
import { isUndefined } from '~/utils/booleanUtil'
import { throwErr } from '~/utils/errorUtil'

enum EnumState {
  WAITING_VALIDATION = 'WAITING_VALIDATION',
  VALIDATED = 'VALIDATED',
  REFUSED = 'REFUSED',
}

enum EnumRole {
  DIRECTOR = 'DIRECTOR',
  MONITOR = 'MONITOR',
  STUDENT = 'STUDENT',
  ADMIN = 'ADMIN',
}

export const SchemaModelUser = z.object({
  id: z.number(),
  createdAt: z.date(),
  updatedAt: z.date(),
  email: z.string().email(),
  password: z.string().min(8),
  role: z.nativeEnum(EnumRole),
})

export class ModelUser extends ModelBasic {
  email = ''
  password = ''
  role = EnumRole.STUDENT
  state = EnumState.WAITING_VALIDATION

  protected constructor(obj?: ModelBase<ModelUser>) {
    if (isUndefined(obj)) {
      super()
      return
    } else if (!objUtil.isObject(obj)) {
      throwErr('obj is not an object')
    } else {
      super(obj)
      objUtil.hydrate(this, SchemaModelUser.parse(obj))
    }
  }
}
