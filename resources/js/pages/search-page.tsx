import { ResultsBox } from '@/components/results-box';
import { SearchBox } from '@/components/search-box';
import { SearchProvider } from '@/contexts/search-context';

export default function SearchPage() {
    return (
        <div className="flex w-full max-w-7xl flex-col items-start gap-4 p-4 md:flex-row xl:px-32">
            <SearchProvider>
                <aside className="w-full shrink-0 md:w-80 lg:w-96">
                    <SearchBox />
                </aside>

                <main className="flex min-h-auto w-full min-w-0 flex-1 md:min-h-3/4 md:w-auto">
                    <ResultsBox />
                </main>
            </SearchProvider>
        </div>
    );
}
