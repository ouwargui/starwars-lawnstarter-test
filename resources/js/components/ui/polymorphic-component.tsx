import type { ComponentPropsWithoutRef, ElementType } from "react";

const DEFAULT_ELEMENT = 'div' as const;

export type PolymorphicComponentProps<T extends ElementType = 'div'> = {
    as?: T;
} & ComponentPropsWithoutRef<T>;

export function PolymorphicComponent<T extends ElementType = 'div'>({ as, ...props }: PolymorphicComponentProps<T>) {
    const Component = as ?? DEFAULT_ELEMENT;

    return <Component {...props} />
}
