import type { ComponentPropsWithoutRef, ElementType } from 'react';

import { cn } from '@/lib/utils';

type TypographyProps<T extends ElementType = 'span'> = {
    as?: T;
    preset?: keyof typeof presets;
} & ComponentPropsWithoutRef<T>;

const presets = {
    'heading-primary': 'text-lg text-black',
    'heading-secondary': '',
    'body-default': 'text-sm text-black',
} as const;

export function Typography<T extends ElementType = 'span'>({
    as,
    className,
    children,
    preset,
    ...props
}: TypographyProps<T>) {
    const Component = as ?? 'span';
    const presetClass = preset ? presets[preset] : null;

    return (
        <Component className={cn(presetClass, className)} {...props}>
            {children}
        </Component>
    );
}
