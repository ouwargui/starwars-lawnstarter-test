import { InertiaFormProps, useForm, usePage } from '@inertiajs/react';
import { createContext, PropsWithChildren, useContext } from 'react';

type SearchResults = {
    name: string;
    id: string;
};

type SearchContextType = {
    filters?: { q: string; type: 'people' | 'movies' };
    results: SearchResults[];
    form: InertiaFormProps<{ q: string; type: 'people' | 'movies' }>;
};

const SearchContext = createContext<SearchContextType | null>(null);

export function SearchProvider(props: PropsWithChildren) {
    const { filters, results } = usePage<{
        filters: { q: string; type: 'people' | 'movies' };
        results: SearchResults[];
    }>().props;
    const form = useForm({ q: filters?.q, type: filters?.type ?? 'people' });

    return (
        <SearchContext.Provider
            value={{
                filters,
                results,
                form,
            }}
        >
            {props.children}
        </SearchContext.Provider>
    );
}

export function useSearch() {
    const context = useContext(SearchContext);

    if (!context) {
        throw new Error('useSearch must be used within a SearchProvider');
    }

    return context;
}
