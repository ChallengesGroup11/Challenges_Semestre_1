import { isUndefined } from './../utils/booleanUtil'
import { z } from 'zod'
import { throwErr } from '~/utils/errorUtil'
import { objUtil } from '~/utils/objectUtil'
import { ModelBase } from '~/utils/type'
import { ModelBasic } from './basicModel'
import { ModelMonitor, SchemaModelMonitor } from './monitorModel'
import { ModelStudent, SchemaModelStudent } from './studentModel'
import { ModelDrivingSchool, SchemaModelDrivingSchool } from './drivingSchoolModel'

export const SchemaModelBooking = z.object({
  student: SchemaModelStudent,
  monitor: SchemaModelMonitor,
  drivingSchool: SchemaModelDrivingSchool,
  slotBegin: z.date(),
  slotEnd: z.date(),
  comment: z.string(),
})

export class ModelBooking extends ModelBasic {
  student!: ModelStudent
  monitor!: ModelMonitor
  drivingSchool!: ModelDrivingSchool
  slotBegin = new Date()
  slotEnd = new Date()
  comment = ''

  protected constructor(obj?: ModelBase<ModelBooking>) {
    if (isUndefined(obj)) {
      super()
      return
    } else if (!objUtil.isObject(obj)) {
      throwErr('obj is not an object')
    } else {
      super(obj)
      objUtil.hydrate(this, SchemaModelBooking.parse(obj))
    }
  }
}
