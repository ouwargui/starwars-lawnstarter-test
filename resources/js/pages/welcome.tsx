import { useState } from 'react';

export default function Welcome() {
    const [searchType, setSearchType] = useState<'people' | 'movies'>('people');
    const [searchQuery, setSearchQuery] = useState('');

    const placeholder =
        searchType === 'people'
            ? 'e.g. Chewbacca, Yoda, Boba Fett'
            : 'e.g. A New Hope, The Empire Strikes Back';

    function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        console.log(e)
        console.log(searchType, searchQuery);
    }

    return (
        <div className="flex flex-1 items-start justify-center gap-4 p-4">
            <aside className="w-full max-w-md">
                <form className="rounded-lg bg-white p-6 shadow-sm" onSubmit={handleSubmit}>
                    <h2 className="mb-4 text-sm font-semibold text-primary">
                        What are you searching for?
                    </h2>

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
                            <span className="text-sm">People</span>
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
                            <span className="text-sm">Movies</span>
                        </label>
                    </div>

                    <div className="mb-4">
                        <input
                            type="text"
                            placeholder={placeholder}
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            className="placeholder-pinkish-gray color-primary font-bold text-sm w-full px-2 py-3 border-pinkish-gray border rounded-sm outline-none focus:border-primary transition-colors"
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={!searchQuery}
                        className="w-full rounded-full disabled:bg-pinkish-gray bg-green-teal py-2 font-bold font-sm text-white transition-colors"
                    >
                        SEARCH
                    </button>
                </form>
            </aside>

            <main className="max-w-2xl flex-1">
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
