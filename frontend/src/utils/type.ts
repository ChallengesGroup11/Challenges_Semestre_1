export interface AnyObject {
  [key: string]: any
}

export type Undefinable<T> = T | undefined

export type Base<T> = T & AnyObject

export type BaseUndefinable<T> = Base<T> | undefined

export type Model = new (obj?: AnyObject) => AnyObject & { id: string; createdAt: Date; updatedAt: Date }
