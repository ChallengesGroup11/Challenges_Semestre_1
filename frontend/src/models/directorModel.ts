import { z } from 'zod'
import { objUtil } from '../utils/objectUtil'
import { User } from './user'
import type { Base } from '~/utils/type'
import { DrivingSchool, SchemaDrivingSchool } from './drivingSchool'

export const SchemaDirector = z.object({
  DrivingSchool: z.undefined().or(SchemaDrivingSchool),
})

export class DirectorModel extends User {
  DrivingSchool?: DrivingSchool = undefined

  protected constructor(obj?: Base<DirectorModel>) {
    if (!objUtil.isObject(obj)) return
    super(obj)
    objUtil.hydrate(this, SchemaDirector.parse(obj))
  }
}
