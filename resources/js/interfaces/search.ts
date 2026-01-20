export interface SearchResults {
    name: string;
    id: string;
};

export interface SearchFilters {
    q: string;
    type: 'people' | 'movies';
}
