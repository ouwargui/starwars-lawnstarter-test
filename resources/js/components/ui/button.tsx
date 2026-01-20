import { ElementType } from "react";

import { cn } from "@/lib/utils";

import { PolymorphicComponent, PolymorphicComponentProps } from "./polymorphic-component";

const DEFAULT_ELEMENT = 'button' as const;

type ButtonProps<T extends ElementType = typeof DEFAULT_ELEMENT> = PolymorphicComponentProps<T>;

export function Button<T extends ElementType = typeof DEFAULT_ELEMENT>({ className, as, ...props }: ButtonProps<T>) {
    const element = as ?? DEFAULT_ELEMENT;

    return (
        <PolymorphicComponent<ElementType> as={element} className={cn("flex items-center justify-center cursor-pointer rounded-full bg-green-teal hover:bg-green-teal-dark py-1 transition-colors disabled:cursor-auto disabled:bg-pinkish-gray", className)} {...props} />
    )
}
