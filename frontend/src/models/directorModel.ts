import { isUndefined } from './../utils/booleanUtil'
import { z } from 'zod'
import { objUtil } from '../utils/objectUtil'
import { ModelUser, User } from './userModel'
import type { ModelBase } from '~/utils/type'
import { DrivingSchool, ModelDrivingSchool, SchemaDrivingSchool } from './drivingSchoolModel'
import { throwErr } from '~/utils/errorUtil'
import { isUndefined } from 'util'

export const SchemaModelDirector = z.object({
  DrivingSchool: z.undefined().or(SchemaDrivingSchool),
})

export class ModelDirector extends ModelUser {
  DrivingSchool?: ModelDrivingSchool = undefined

  protected constructor(obj?: ModelBase<ModelDirector>) {
    if (isUndefined(obj)) {
      super()
      return
    } else if (!objUtil.isObject(obj)) {
      throwErr('obj is not an object')
    } else {
      super(obj)
      objUtil.hydrate(this, SchemaModelDirector.parse(obj))
    }
  }
}
