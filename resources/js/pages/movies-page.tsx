import { Link, usePage } from "@inertiajs/react";

import { DetailsBox } from "@/components/details-box";
import { Button } from "@/components/ui/button";
import { Typography } from "@/components/ui/typography";
import { MovieDetails } from "@/interfaces/movie";
import { people, search } from "@/routes";

export default function MoviesPage() {
    const { movie } = usePage<{ movie: MovieDetails }>().props;

    return (
        <DetailsBox.Root>
            <DetailsBox.Header>{movie.title}</DetailsBox.Header>
            <DetailsBox.Content>
                <DetailsBox.Aside>
                    <DetailsBox.AsideHeader>Opening Crawl</DetailsBox.AsideHeader>
                    <DetailsBox.AsideContent>
                        {movie.openingCrawl}
                    </DetailsBox.AsideContent>
                </DetailsBox.Aside>
                <DetailsBox.Aside>
                    <DetailsBox.AsideHeader>Characters</DetailsBox.AsideHeader>
                    <DetailsBox.AsideContent>
                        {movie.people.map((person, index) => (
                            <span key={person.id}>
                                <Link className="text-emerald hover:underline" href={people({ id: person.id })}>
                                    {person.name}
                                </Link>
                                {index < movie.people.length - 1 ? ", " : null}
                            </span>
                        ))}
                    </DetailsBox.AsideContent>
                </DetailsBox.Aside>
            </DetailsBox.Content>
            <DetailsBox.Footer>
                <Button as={Link} href={search()} className="self-end w-full max-w-md mx-auto md:mx-0 md:w-auto">
                    <Typography preset="body-default" className="font-bold text-white uppercase px-4">BACK TO SEARCH</Typography>
                </Button>
            </DetailsBox.Footer>
        </DetailsBox.Root>
    );
}
