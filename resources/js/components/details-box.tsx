import { PropsWithChildren } from "react";

import { Box } from "./ui/box";
import { Divider } from "./ui/divider";
import { Typography } from "./ui/typography";

function DetailsBoxRoot(props: PropsWithChildren) {
    return (
        <main className="flex flex-1 justify-center items-start px-4 pb-4">
            <Box.Root className="w-full md:max-w-2/3 min-h-full md:min-h-2/3 gap-8">
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
        <Box.Content className="flex-col md:flex-row md:justify-between w-full gap-12 lg:gap-24">
            {props.children}
        </Box.Content>
    );
}

function DetailsBoxAside(props: PropsWithChildren) {
    return (
        <div className="flex flex-col gap-1 flex-1">
            {props.children}
        </div>
    );
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
    return (
        <Box.Footer className="flex flex-1 w-full">
            {props.children}
        </Box.Footer>
    );
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
