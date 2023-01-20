export interface AnyObject {
  [key: string]: any
}

export interface InterfaceBasicModel {
  id: number
  createdAt: Date
  updatedAt: Date
}

export type Undefinable<T> = T | undefined

export type ModelBase<T> = T & InterfaceBasicModel

export type BaseUndefinable<T> = ModelBase<T> | undefined

export type Model = new (obj?: AnyObject) => AnyObject & { id: string; createdAt: Date; updatedAt: Date }
