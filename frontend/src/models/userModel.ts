import { ModelBasic } from './basicModel'
import { z } from 'zod'
import { objUtil } from '../utils/objectUtil'
import type { Base } from '~/utils/type'

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

  protected constructor(obj?: Base<UserModel>) {
    if (!objUtil.isObject(obj)) return
    objUtil.hydrate(this, UserSchema.parse(obj))
  }
}
