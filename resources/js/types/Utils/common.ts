export type UseRoute = (name?: string, params?: string | number | object | any[], k?: any) => any

export type PathsToStringProps<T> = T extends string ? [] : {
    [K in Extract<keyof T, string>]: [K, ...PathsToStringProps<T[K]>]
}[Extract<keyof T, string>];

export type Join<T extends string[], D extends string> =
    T extends [] ? never :
        T extends [infer F] ? F :
            T extends [infer F, ...infer R] ?
                F extends string ?
                    `${F}${D}${Join<Extract<R, string[]>, D>}` : never : string;

export type AnyObject = Record<string, any>

export type DotJoin<A extends string, B extends string> = A extends '' ? B : `${A}.${B}`

export type DeepKeys<O extends AnyObject> = {
    [K in keyof O]: O[K] extends AnyObject ? K : never
}[keyof O]

// @ts-expect-error Type 'keyof O' does not satisfy the constraint 'string'.
export  type DotBranch<O extends AnyObject, P extends string = '', K extends string = keyof O> =
    K extends DeepKeys<O>
        ? DotBranch<O[K], DotJoin<P, K>>
        : /*************/ DotJoin<P, K>
