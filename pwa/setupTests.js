import { SchemaModelStudent } from "../studentModel";
// optional: configure or set up a testing framework before each test
// if you delete this file, remove `setupFilesAfterEnv` from `jest.config.js`

// used for __tests__/testing-library.js
// learn more: https://github.com/testing-library/jest-dom
import "@testing-library/jest-dom/extend-expect";

export const SchemaModelBooking = z.object({
  student: SchemaModelStudent,
  monitor: SchemaMonitor,
  drivingSchool: SchemaDrivingSchool,
  slotBegin: z.date(),
  slotEnd: z.date(),
  comment: z.string(),
});
