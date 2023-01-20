import { z } from 'zod'
import { isUndefined } from '~/utils/booleanUtil'
import { throwErr } from '~/utils/errorUtil'
import { objUtil } from '~/utils/objectUtil'
import { ModelBase } from '~/utils/type'
import { DrivingSchool, SchemaDrivingSchool } from './drivingSchoolModel'
import { ModelUser, User } from './userModel'

export const SchemaMonitorModel = z.object({
  drivingSchool: SchemaDrivingSchool,
})

export class ModelMonitor extends ModelUser {
  drivingSchool?: DrivingSchool = undefined

  protected constructor(obj?: ModelBase<ModelMonitor>) {
    if (isUndefined(obj)) {
      super()
      return
    } else if (!objUtil.isObject(obj)) {
      throwErr('obj is not an object')
    } else {
      super(obj)
      objUtil.hydrate(this, SchemaMonitorModel.parse(obj))
    }
  }
}
