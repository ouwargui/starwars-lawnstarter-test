import { PropsWithChildren } from 'react';

import { useSearch } from '@/contexts/search-context';

import { Box } from './ui/box';
import { Button } from './ui/button';
import { Divider } from './ui/divider';
import { Typography } from './ui/typography';

export function ResultsBox() {
    const { searchQuery, submittedSearch } = useSearch();

    const hasSearched = submittedSearch !== '';
    const hasData =
        searchQuery.isSuccess && searchQuery.data && searchQuery.data.length > 0;
    const isFetching = searchQuery.isFetching;
    const isError = searchQuery.isError;
    const showEmpty = !hasSearched || (searchQuery.isSuccess && !hasData);

    return (
        <Box.Root className="flex-1">
            <Box.Header className="flex flex-col gap-2">
                <Typography as="h1" preset="heading-primary" className="font-bold">
                    Results
                </Typography>
                <Divider />
            </Box.Header>

            <Box.Content className="relative flex-1 flex-col">
                {isFetching && <EmptyResults>Searching...</EmptyResults>}

                {isError && (
                    <EmptyResults>
                        An error occurred while searching. See the logs for more details.
                    </EmptyResults>
                )}

                {showEmpty && !isFetching && !isError && (
                    <EmptyResults>
                        There are zero matches.
                        <br />
                        Use the form to search for People or Movies.
                    </EmptyResults>
                )}

                {hasData &&
                    searchQuery.data.map((result, index) => (
                        <div className="flex flex-col gap-4" key={index}>
                            <div className="flex items-center justify-between">
                                <Typography
                                    as="span"
                                    preset="heading-primary"
                                    className="font-bold"
                                >
                                    {result}
                                </Typography>
                                <Button type="button" className="px-3">
                                    <Typography
                                        preset="body-default"
                                        className="font-bold text-white uppercase"
                                    >
                                        SEE DETAILS
                                    </Typography>
                                </Button>
                            </div>
                            <Divider />
                        </div>
                    ))}
            </Box.Content>
        </Box.Root>
    );
}

function EmptyResults(props: PropsWithChildren) {
    return (
        <div className="absolute inset-0 flex items-center justify-center">
            <Typography
                preset="body-default"
                className="w-max text-center font-bold text-pinkish-gray"
            >
                {props.children}
            </Typography>
        </div>
    );
}
