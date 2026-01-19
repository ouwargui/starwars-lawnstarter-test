import { useQuery, UseQueryResult } from '@tanstack/react-query';
import { createContext, PropsWithChildren, useContext, useState } from 'react';

import { search } from '@/services/search.service';

type SearchContextType = {
    submittedSearch: string;
    submittedSearchType: 'people' | 'movies';
    submitSearch: (search: string, searchType: 'people' | 'movies') => void;
    searchQuery: UseQueryResult<string[], Error>;
}

const SearchContext = createContext<SearchContextType | null>(null);

export function SearchProvider(props: PropsWithChildren) {
    const [submittedSearch, setSubmittedSearch] = useState('');
    const [submittedSearchType, setSubmittedSearchType] = useState<'people' | 'movies'>('people');

    const submitSearch = (search: string, searchType: 'people' | 'movies') => {
        setSubmittedSearch(search);
        setSubmittedSearchType(searchType);
    };

    const searchQuery = useQuery({
        queryFn: () => search(submittedSearch, submittedSearchType),
        queryKey: ['search', submittedSearch, submittedSearchType],
        enabled: submittedSearch !== '',
    });

    return (
        <SearchContext.Provider
            value={{
                submittedSearch,
                submittedSearchType,
                submitSearch,
                searchQuery,
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
