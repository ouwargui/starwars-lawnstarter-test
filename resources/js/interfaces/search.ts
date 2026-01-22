export interface SearchResults {
    name: string;
    id: string;
}

export interface SearchFilters {
    q: string;
    type: 'people' | 'movies';
}

export interface SearchPageProps extends Record<string, SearchResults[] | SearchFilters> {
    filters: SearchFilters;
    results: SearchResults[];
}
