interface PersonSummary {
    id: number;
    name: string;
}

export interface MovieDetails {
    title: string;
    openingCrawl: string;
    people: PersonSummary[];
}
