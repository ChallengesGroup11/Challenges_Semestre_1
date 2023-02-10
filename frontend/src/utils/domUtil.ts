import { throwErr } from './errorUtil'
export namespace domUtil {
  export const handleFileUpload = (event: Event & { target: HTMLInputElement }): File => {
    return event?.target?.files?.[0] ?? throwErr('No file selected')
  }
}
