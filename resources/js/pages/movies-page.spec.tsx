import { usePage } from '@inertiajs/react';
import { describe, it, vi } from 'vitest';
import { render } from 'vitest-browser-react';

import { MovieDetails } from '@/interfaces/movie';

import MoviesPage from './movies-page';

vi.mock('@inertiajs/react');

vi.mocked(usePage<{ movie: MovieDetails }>).mockReturnValue({
    // @ts-expect-error - we are omitting the rest of the page props
    props: {
        movie: {
            openingCrawl: 'It is a period of civil war...',
            title: 'A New Hope',
            people: [{ id: 1, name: 'Luke Skywalker' }],
        },
    },
});

describe('MoviesPage', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<MoviesPage />);
        expect(container).toMatchSnapshot();
    });
});
