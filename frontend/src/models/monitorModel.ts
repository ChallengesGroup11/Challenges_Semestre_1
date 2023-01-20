import { z } from 'zod'
import { isUndefined } from '~/utils/booleanUtil'
import { throwErr } from '~/utils/errorUtil'
import { objUtil } from '~/utils/objectUtil'
import { ModelBase } from '~/utils/type'
import { ModelDrivingSchool, SchemaModelDrivingSchool } from './drivingSchoolModel'
import { ModelUser } from './userModel'

export const SchemaModelMonitor = z.object({
  drivingSchool: SchemaModelDrivingSchool,
})

export class ModelMonitor extends ModelUser {
  drivingSchool?: ModelDrivingSchool = undefined

  protected constructor(obj?: ModelBase<ModelMonitor>) {
    if (isUndefined(obj)) {
      super()
      return
    } else if (!objUtil.isObject(obj)) {
      throwErr('obj is not an object')
    } else {
      super(obj)
      objUtil.hydrate(this, SchemaModelMonitor.parse(obj))
    }
  }
}
