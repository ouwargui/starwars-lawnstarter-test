import { ComponentPropsWithoutRef } from "react";

import { cn } from "@/lib/utils";

export function Button({ className, ...props }: ComponentPropsWithoutRef<'button'>) {
    return (
        <button
            className={cn("cursor-pointer rounded-full bg-green-teal hover:bg-green-teal-dark py-1 transition-colors disabled:cursor-auto disabled:bg-pinkish-gray", className)}
            {...props}
        />
    )
}
