import { PropsWithChildren } from "react";

export function PageLayout(props: PropsWithChildren) {
    return (
        <div className="min-h-screen flex flex-col bg-background">
            <header className="w-full flex items-center justify-center bg-white inset-ring-1 inset-ring-light-gray">
                <span className="text-green-teal font-bold text-xl py-4">SWStarter</span>
            </header>
            <main className="flex-1">{props.children}</main>
        </div>
    )
}
