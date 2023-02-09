import { isUndefined } from './../utils/booleanUtil'
import { z } from 'zod'
import { objUtil } from '../utils/objectUtil'

import { throwErr } from '~/utils/errorUtil'
import { ModelDrivingSchool, SchemaModelDrivingSchool } from './drivingSchoolModel'
import { ModelUser } from './userModel'
import { ModelBase } from '~/utils/type'

export const SchemaModelDirector = z.object({
  drivingSchool: z.undefined().or(SchemaModelDrivingSchool),
})

export class ModelDirector extends ModelUser {
  drivingSchool?: ModelDrivingSchool = undefined

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
