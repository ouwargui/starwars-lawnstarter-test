import { Link } from '@inertiajs/react';
import { PropsWithChildren } from 'react';

import { Typography } from './ui/typography';

export function PageLayout(props: PropsWithChildren) {
    return (
        <div className="flex min-h-screen flex-col gap-4 bg-background">
            <header className="flex w-full items-center justify-center bg-white inset-ring-1 inset-ring-light-gray">
                <Typography as={Link} preset="logo" className="py-4" href="/">
                    SWStarter
                </Typography>
            </header>
            <div className="flex flex-1 justify-center">{props.children}</div>
        </div>
    );
}
