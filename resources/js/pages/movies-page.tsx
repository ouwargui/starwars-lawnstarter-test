import { Link } from "@inertiajs/react";

import { DetailsBox } from "@/components/details-box";
import { Button } from "@/components/ui/button";
import { Typography } from "@/components/ui/typography";
import { movies, search } from "@/routes";

export default function MoviesPage() {
    return (
        <DetailsBox.Root>
            <DetailsBox.Header>Movie Name</DetailsBox.Header>
            <DetailsBox.Content>
                <DetailsBox.Aside>
                    <DetailsBox.AsideHeader>Opening Crawl</DetailsBox.AsideHeader>
                    <DetailsBox.AsideContent>
                        Gender: Gender<br />
                        Height: Height<br />
                        Mass: Mass<br />
                    </DetailsBox.AsideContent>
                </DetailsBox.Aside>
                <DetailsBox.Aside>
                    <DetailsBox.AsideHeader>Characters</DetailsBox.AsideHeader>
                    <DetailsBox.AsideContent>
                        <Link href={movies({ id: 1 })}>return of the jedi</Link><br />
                        <Link href={movies({ id: 2 })}>return of the jedi</Link><br />
                        <Link href={movies({ id: 3 })}>return of the jedi</Link><br />
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
