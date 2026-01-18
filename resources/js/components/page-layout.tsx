import { PropsWithChildren } from 'react';

export function PageLayout(props: PropsWithChildren) {
    return (
        <div className="flex min-h-screen flex-col gap-4 bg-background">
            <header className="flex w-full items-center justify-center bg-white inset-ring-1 inset-ring-light-gray">
                <span className="py-4 text-xl font-bold text-green-teal">SWStarter</span>
            </header>
            <div className="flex-1 flex justify-center">{props.children}</div>
        </div>
    );
}
