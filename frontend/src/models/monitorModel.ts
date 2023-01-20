import { z } from 'zod'
import { objUtil } from '~/utils/objectUtil'
import { Base } from '~/utils/type'
import { DrivingSchool, SchemaDrivingSchool } from './drivingSchoolModel'
import { User } from './user'

export const SchemaMonitorModel = z.object({
  drivingSchool: SchemaDrivingSchool,
})

export class MonitorModel extends User {
  drivingSchool?: DrivingSchool = undefined

  protected constructor(obj?: Base<MonitorModel>) {
    if (!objUtil.isObject(obj)) return
    super(obj)
    objUtil.hydrate(this, SchemaMonitorModel.parse(obj))
  }
}
