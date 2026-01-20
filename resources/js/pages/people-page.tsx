import { Link, usePage } from "@inertiajs/react";

import { DetailsBox } from "@/components/details-box";
import { Button } from "@/components/ui/button";
import { Typography } from "@/components/ui/typography";
import { PersonDetails } from "@/interfaces/person";
import { movies, search } from "@/routes";

export default function PeoplePage() {
    const { person } = usePage<{ person: PersonDetails }>().props;

    return (
        <DetailsBox.Root>
            <DetailsBox.Header>{person.name}</DetailsBox.Header>
            <DetailsBox.Content>
                <DetailsBox.Aside>
                    <DetailsBox.AsideHeader>Details</DetailsBox.AsideHeader>
                    <DetailsBox.AsideContent>
                        Birth Year: {person.birthYear}<br />
                        Gender: {person.gender}<br />
                        Eye Color: {person.eyeColor}<br />
                        Hair Color: {person.hairColor}<br />
                        Height: {person.height}<br />
                        Mass: {person.mass}<br />
                    </DetailsBox.AsideContent>
                </DetailsBox.Aside>
                <DetailsBox.Aside>
                    <DetailsBox.AsideHeader>Movies</DetailsBox.AsideHeader>
                    <DetailsBox.AsideContent>
                        {person.films.map((film, index) => (
                            <span key={film.id}>
                                <Link className="text-emerald hover:underline" href={movies({ id: film.id })}>
                                    {film.title}
                                </Link>
                                {index < person.films.length - 1 ? ", " : null}
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
