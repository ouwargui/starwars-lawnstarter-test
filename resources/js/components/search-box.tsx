import { Fragment, useState } from 'react';

import { useSearch } from '@/contexts/search-context';

import { Box } from './ui/box';
import { Button } from './ui/button';
import { Typography } from './ui/typography';

const PLACEHOLDERS = {
    people: 'e.g. Chewbacca, Yoda, Boba Fett',
    movies: 'e.g. A New Hope, The Empire Strikes Back',
} as const;

export function SearchBox() {
    const [searchText, setSearchText] = useState('');
    const [searchType, setSearchType] = useState<'people' | 'movies'>('people');

    const { submitSearch } = useSearch();

    function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();

        if (!searchText || !searchType) return;

        submitSearch(searchText, searchType);
    }

    return (
        <Box.Root as="form" onSubmit={handleSubmit}>
            <Box.Header>
                <Typography
                    as="h2"
                    preset="body-default"
                    className="font-semibold text-primary"
                >
                    What are you searching for?
                </Typography>
            </Box.Header>

            <Box.Content as="fieldset" className="flex-wrap font-bold">
                <label className="flex items-center gap-2">
                    <input
                        type="radio"
                        value="people"
                        checked={searchType === 'people'}
                        onChange={(e) => setSearchType(e.target.value as 'people')}
                        className="h-4 w-4 accent-emerald"
                    />
                    <Typography preset="body-default">People</Typography>
                </label>
                <label className="flex items-center gap-2">
                    <input
                        type="radio"
                        value="movies"
                        checked={searchType === 'movies'}
                        onChange={(e) => setSearchType(e.target.value as 'movies')}
                        className="h-4 w-4 accent-emerald"
                    />
                    <Typography preset="body-default">Movies</Typography>
                </label>
            </Box.Content>

            <Box.Footer as={Fragment}>
                <Typography
                    as="input"
                    preset="body-default"
                    type="text"
                    placeholder={PLACEHOLDERS[searchType]}
                    value={searchText}
                    onChange={(e) => setSearchText(e.target.value)}
                    className="w-full rounded-sm border border-pinkish-gray px-2 py-3 font-bold text-primary placeholder-pinkish-gray transition-colors outline-none focus:border-primary"
                />

                <Button
                    type="submit"
                    disabled={!searchText}
                    className="w-full"
                >
                    <Typography
                        preset="body-default"
                        className="font-bold text-white uppercase"
                    >
                        SEARCH
                    </Typography>
                </Button>
            </Box.Footer>
        </Box.Root>
    );
}
