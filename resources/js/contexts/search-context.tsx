import { InertiaFormProps, useForm, usePage } from '@inertiajs/react';
import { createContext, PropsWithChildren, useContext } from 'react';

import { SearchFilters, SearchPageProps, SearchResults } from '@/interfaces/search';


type SearchContextType = {
    filters?: SearchFilters;
    results: SearchResults[];
    form: InertiaFormProps<SearchFilters>;
};

const SearchContext = createContext<SearchContextType | null>(null);

export function SearchProvider(props: PropsWithChildren) {
    const { filters, results } = usePage<SearchPageProps>().props;
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
