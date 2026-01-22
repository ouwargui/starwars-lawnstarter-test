import { PropsWithChildren } from 'react';

import { Box } from './ui/box';
import { Divider } from './ui/divider';
import { Typography } from './ui/typography';

function DetailsBoxRoot(props: PropsWithChildren) {
    return (
        <main className="flex flex-1 items-start justify-center px-4 pb-4">
            <Box.Root className="min-h-full w-full gap-8 md:min-h-2/3 md:max-w-2/3">
                {props.children}
            </Box.Root>
        </main>
    );
}

function DetailsBoxHeader(props: PropsWithChildren) {
    return (
        <Box.Header>
            <Typography as="h1" preset="heading-primary" className="font-bold">
                {props.children}
            </Typography>
        </Box.Header>
    );
}

function DetailsBoxContent(props: PropsWithChildren) {
    return (
        <Box.Content className="w-full flex-col gap-12 md:flex-row md:justify-between lg:gap-24">
            {props.children}
        </Box.Content>
    );
}

function DetailsBoxAside(props: PropsWithChildren) {
    return <div className="flex flex-1 flex-col gap-1">{props.children}</div>;
}

function DetailsBoxAsideHeader(props: PropsWithChildren) {
    return (
        <div className="flex flex-col gap-2">
            <Typography as="h2" preset="heading-secondary" className="font-bold">
                {props.children}
            </Typography>
            <Divider />
        </div>
    );
}

function DetailsBoxAsideContent(props: PropsWithChildren) {
    return (
        <Typography as="p" preset="body-default" className="whitespace-pre-line">
            {props.children}
        </Typography>
    );
}

function DetailsBoxFooter(props: PropsWithChildren) {
    return <Box.Footer className="flex w-full flex-1">{props.children}</Box.Footer>;
}

export const DetailsBox = {
    Root: DetailsBoxRoot,
    Header: DetailsBoxHeader,
    Content: DetailsBoxContent,
    Aside: DetailsBoxAside,
    AsideHeader: DetailsBoxAsideHeader,
    AsideContent: DetailsBoxAsideContent,
    Footer: DetailsBoxFooter,
};
