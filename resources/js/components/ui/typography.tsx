import { ElementType } from 'react';

import { cn } from '@/lib/utils';

import { PolymorphicComponent, PolymorphicComponentProps } from './polymorphic-component';

const DEFAULT_ELEMENT = 'span' as const;

type TypographyProps<T extends ElementType = typeof DEFAULT_ELEMENT> = {
    preset?: keyof typeof presets;
} & PolymorphicComponentProps<T>;

const presets = {
    'heading-primary': 'text-lg text-black',
    'heading-secondary': '',
    'body-default': 'text-sm text-black',
} as const;

export function Typography<T extends ElementType = typeof DEFAULT_ELEMENT>({
    className,
    preset,
    as,
    ...props
}: TypographyProps<T>) {
    const presetClass = preset ? presets[preset] : null;
    const element = as ?? DEFAULT_ELEMENT;

    return (
        <PolymorphicComponent<ElementType> as={element} className={cn(presetClass, className)} {...props} />
    );
}
