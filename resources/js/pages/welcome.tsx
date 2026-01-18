import { useState } from 'react';

import { Typography } from '@/components/typography';

export default function Welcome() {
    const [searchType, setSearchType] = useState<'people' | 'movies'>('people');
    const [searchQuery, setSearchQuery] = useState('');

    const placeholder =
        searchType === 'people'
            ? 'e.g. Chewbacca, Yoda, Boba Fett'
            : 'e.g. A New Hope, The Empire Strikes Back';

    function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        console.log(searchType, searchQuery);
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

                    <div className="mb-4 flex gap-4 font-bold text-black">
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
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            className="w-full rounded-sm border border-pinkish-gray px-2 py-3 font-bold text-primary placeholder-pinkish-gray transition-colors outline-none focus:border-primary"
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={!searchQuery}
                        className="w-full cursor-pointer rounded-full bg-green-teal py-2 transition-colors disabled:cursor-auto disabled:bg-pinkish-gray"
                    >
                        <Typography preset="body-default" className="font-bold text-white uppercase">SEARCH</Typography>
                    </button>
                </form>
            </aside>

            <main className="min-w-0 w-full md:w-auto flex-1">
                <div className="rounded-lg bg-white p-6 shadow-sm">
                    <h2 className="mb-4 text-lg font-semibold">Results</h2>
                    <div className="text-gray-600">
                        There are zero matches. Use the form to search for People or Movie
                    </div>
                </div>
            </main>
        </div>
    );
}
