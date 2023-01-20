import { MonitorModel } from './monitorModel'
import { DrivingSchoolModel } from './drivingSchoolModel'
import { z } from 'zod'
import { objUtil } from '~/utils/objectUtil'
import type { Base } from '~/utils/type'
import { ModelBooking } from './bookingModel'
import { DirectorModel } from './directorModel'
import { UserModel } from './user'
import { ModelStudent } from './student'
import { ModelPayment } from './payment'
import { ModelPackage } from './packageModel'

const SchemaModelBasic = z.object({
  id: z.number(),
  createdAt: z.date(),
  updatedAt: z.date(),
})

export class ModelBasic {
  id = 0
  createdAt = new Date()
  updatedAt = new Date()

  protected constructor(obj?: Base<ModelBasic>) {
    if (!objUtil.isObject(obj)) return
    objUtil.hydrate(this, SchemaModelBasic.parse(obj))
  }
}

// export const EnumModel = {
//   BASIC_MODEL: BasicModel,
//   BOOKING: BookingModel,
//   DIRECTOR: DirectorModel,
//   DRIVING_SCHOOL: DrivingSchoolModel,
//   MONITOR: MonitorModel,
//   PACKAGE: PackageModel,
//   PAYMENT: PaymentModel,
//   STUDENT: StudentModel,
//   USER: UserModel,
// } as const
