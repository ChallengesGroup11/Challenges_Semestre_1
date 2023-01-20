import type { AnyObject, Base, BaseUndefinable, Class, Undefinable } from '~/utils/type'
import { ModelBasic, EnumModel } from './basicModel'
import { z } from 'zod'
import { Monitor, SchemaMonitor } from './monitorModel'
import { DrivingSchool, SchemaDrivingSchool } from './drivingSchoolModel'
import { objUtil } from '~/utils/objectUtil'
import { SchemaModelStudent, Student } from './studentModel'
import { throwErr } from '../utils/errorUtil'

import * as R from 'ramda'
import { boolUtil } from '~/utils/booleanUtil'
import { isUndefined } from '../utils/booleanUtil'
import { Model } from '../utils/type'
import { ModelUser } from './userModel'

export const SchemaModelBooking = z.object({
  student: SchemaModelStudent,
  monitor: SchemaMonitor,
  drivingSchool: SchemaDrivingSchool,
  slotBegin: z.date(),
  slotEnd: z.date(),
  comment: z.string(),
})

export class ModelBooking extends ModelBasic {
  student!: Student
  monitor!: Monitor
  drivingSchool!: DrivingSchool
  slotBegin = new Date()
  slotEnd = new Date()
  comment = ''

  protected constructor(obj?: Base<ModelBooking>) {
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

  // static make = (obj: Base<BookingModel>): Undefinable<BookingModel> => {
  //   if (!obj) return undefined
  //   try {
  //     return new BookingModel(obj)
  //   } catch (e) {
  //     return undefined
  //   }
  // }
}

// pipe char

export const make = <T extends Model>(Model: Model, obj: AnyObject): T | undefined => {
  return R.tryCatch(() => new Model(obj), R.always(undefined))()
}

const user = make<ModelUser>(ModelUser, { name: 'John' })
