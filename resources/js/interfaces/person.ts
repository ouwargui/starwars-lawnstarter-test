interface FilmSummary {
    id: number;
    title: string;
}

export interface PersonDetails {
    birthYear: string;
    eyeColor: string;
    films: FilmSummary[];
    gender: string;
    hairColor: string;
    height: string;
    mass: string;
    name: string;
}
