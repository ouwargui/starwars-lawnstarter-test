import type { ElementType } from "react";

import { cn } from "@/lib/utils";

import { PolymorphicComponent, PolymorphicComponentProps } from "./polymorphic-component";

const DEFAULT_ELEMENT = 'div' as const;

type BoxRootProps<T extends ElementType = typeof DEFAULT_ELEMENT> = PolymorphicComponentProps<T>;

function BoxRoot<T extends ElementType = typeof DEFAULT_ELEMENT>({ as, className, ...props }: BoxRootProps<T>) {
    const element = as ?? DEFAULT_ELEMENT;

    return (
        <PolymorphicComponent<ElementType> as={element} className={cn("rounded-lg bg-white p-6 shadow-sm flex flex-col gap-4", className)} {...props} />
    );
}

type BoxHeaderProps<T extends ElementType = typeof DEFAULT_ELEMENT> = PolymorphicComponentProps<T>;

function BoxHeader<T extends ElementType = typeof DEFAULT_ELEMENT>({ as, className, ...props }: BoxHeaderProps<T>) {
    const element = as ?? DEFAULT_ELEMENT;

    return (
        <PolymorphicComponent<ElementType> as={element} className={className} {...props} />
    );
}

type BoxContentProps<T extends ElementType = typeof DEFAULT_ELEMENT> = PolymorphicComponentProps<T>;

function BoxContent<T extends ElementType = typeof DEFAULT_ELEMENT>({ as, className, ...props }: BoxContentProps<T>) {
    const element = as ?? DEFAULT_ELEMENT;

    return (
        <PolymorphicComponent<ElementType> as={element} className={cn("flex gap-4", className)} {...props} />
    );
}

type BoxFooterProps<T extends ElementType = typeof DEFAULT_ELEMENT> = PolymorphicComponentProps<T>;

function BoxFooter<T extends ElementType = typeof DEFAULT_ELEMENT>({ as, className, ...props }: BoxFooterProps<T>) {
    const element = as ?? DEFAULT_ELEMENT;

    return (
        <PolymorphicComponent<ElementType> as={element} className={className} {...props} />
    );
}

export const Box = {
    Root: BoxRoot,
    Header: BoxHeader,
    Content: BoxContent,
    Footer: BoxFooter,
}
