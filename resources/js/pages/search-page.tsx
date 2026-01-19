import { useQuery } from '@tanstack/react-query';
import { useState } from 'react';

import { Divider } from '@/components/ui/divider';
import { Typography } from '@/components/ui/typography';
import { search } from '@/services/search.service';

export default function SearchPage() {
    const [searchType, setSearchType] = useState<'people' | 'movies'>('people');
    const [searchText, setSearchText] = useState('');
    const [submittedSearch, setSubmittedSearch] = useState('');
    const searchQuery = useQuery({
        queryFn: () => search(submittedSearch),
        queryKey: ['search', submittedSearch],
        enabled: submittedSearch !== '',
    });

    const placeholder =
        searchType === 'people'
            ? 'e.g. Chewbacca, Yoda, Boba Fett'
            : 'e.g. A New Hope, The Empire Strikes Back';

    const hasSearched = submittedSearch !== '';
    const hasData = searchQuery.isSuccess && searchQuery.data && searchQuery.data.length > 0;
    const isFetching = searchQuery.isFetching;
    const isError = searchQuery.isError;
    const showEmpty = !hasSearched || (searchQuery.isSuccess && !hasData);

    function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();

        if (!searchText || !searchType) return;

        setSubmittedSearch(searchText);
    }

    return (
        <div className="max-w-7xl flex w-full flex-col items-start gap-4 p-4 xl:px-32 md:flex-row">
            <aside className="w-full shrink-0 md:w-80 lg:w-96">
                <form
                    className="rounded-lg bg-white p-6 shadow-sm"
                    onSubmit={handleSubmit}
                >
                    <Typography
                        as="h2"
                        preset="body-default"
                        className="mb-4 font-semibold text-primary"
                    >
                        What are you searching for?
                    </Typography>

                    <div className="mb-4 flex gap-4 font-bold flex-wrap">
                        <label className="flex items-center gap-2">
                            <input
                                type="radio"
                                name="searchType"
                                value="people"
                                checked={searchType === 'people'}
                                onChange={(e) =>
                                    setSearchType(e.target.value as 'people')
                                }
                                className="h-4 w-4 accent-emerald"
                            />
                            <Typography preset="body-default">People</Typography>
                        </label>
                        <label className="flex items-center gap-2">
                            <input
                                type="radio"
                                value="movies"
                                checked={searchType === 'movies'}
                                onChange={(e) =>
                                    setSearchType(e.target.value as 'movies')
                                }
                                className="h-4 w-4 accent-emerald"
                            />
                            <Typography preset="body-default">Movies</Typography>
                        </label>
                    </div>

                    <div className="mb-4">
                        <Typography
                            as="input"
                            preset="body-default"
                            type="text"
                            placeholder={placeholder}
                            value={searchText}
                            onChange={(e) => setSearchText(e.target.value)}
                            className="w-full rounded-sm border border-pinkish-gray px-2 py-3 font-bold text-primary placeholder-pinkish-gray transition-colors outline-none focus:border-primary"
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={!searchText}
                        className="w-full cursor-pointer rounded-full bg-green-teal hover:bg-green-teal-dark py-1 transition-colors disabled:cursor-auto disabled:bg-pinkish-gray"
                    >
                        <Typography preset="body-default" className="font-bold text-white uppercase">SEARCH</Typography>
                    </button>
                </form>
            </aside>

            <main className="min-w-0 min-h-auto md:min-h-3/4 w-full md:w-auto flex-1 flex">
                <div className="rounded-lg bg-white p-6 shadow-sm flex-1 flex flex-col relative gap-4">
                    <div className="flex flex-col gap-2">
                        <Typography as="h1" preset="heading-primary" className="font-bold">Results</Typography>
                        <Divider />
                    </div>

                    {isFetching && (
                        <div className="absolute inset-0 flex items-center justify-center">
                            <Typography preset="body-default" className="w-max text-center font-bold text-pinkish-gray">
                                Searching...
                            </Typography>
                        </div>
                    )}

                    {isError && (
                        <div className="absolute inset-0 flex items-center justify-center">
                            <Typography preset="body-default" className="w-max text-center font-bold text-pinkish-gray">
                                An error occurred while searching. See the logs for more details.
                            </Typography>
                        </div>
                    )}

                    {hasData && searchQuery.data.map((result, index) => (
                        <div className="flex flex-col gap-4" key={index}>
                            <div className="flex items-center justify-between">
                                <Typography as="span" preset="heading-primary" className="font-bold">{result}</Typography>
                                <button type="button" className="cursor-pointer rounded-full bg-green-teal hover:bg-green-teal-dark py-1 px-3 transition-colors">
                                    <Typography preset="body-default" className="font-bold text-white uppercase">SEE DETAILS</Typography>
                                </button>
                            </div>
                            <Divider />
                        </div>
                    ))}

                    {showEmpty && !isFetching && !isError && (
                        <div className="absolute inset-0 flex items-center justify-center">
                            <Typography preset="body-default" className="w-max text-center font-bold text-pinkish-gray">
                                There are zero matches.<br />
                                Use the form to search for People or Movies.
                            </Typography>
                        </div>
                    )}
                </div>
            </main>
        </div>
    );
}
