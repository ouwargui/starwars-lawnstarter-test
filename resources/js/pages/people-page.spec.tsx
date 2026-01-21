import { usePage } from "@inertiajs/react";
import { describe, it, vi } from "vitest";
import { render } from "vitest-browser-react";

import { PersonDetails } from "@/interfaces/person";

import PeoplePage from "./people-page";

vi.mock('@inertiajs/react');

vi.mocked(usePage<{ person: PersonDetails }>).mockReturnValue(
    {
        // @ts-expect-error - we are omitting the rest of the page props
        props: {
            person: {
                name: 'Luke Skywalker',
                birthYear: '19BBY',
                eyeColor: 'blue',
                gender: 'male',
                hairColor: 'blond',
                height: '172',
                mass: '77',
                films: [{ id: 1, title: 'A New Hope' }]
            },
        },
    }
);

describe('PeoplePage', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<PeoplePage />);
        expect(container).toMatchSnapshot();
    });
});
