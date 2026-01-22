import { usePage } from '@inertiajs/react';
import { describe, it, vi } from 'vitest';
import { render } from 'vitest-browser-react';

import { SearchPageProps } from '@/interfaces/search';

import SearchPage from './search-page';

vi.mock('@inertiajs/react');

vi.mocked(usePage<SearchPageProps>).mockReturnValue({
    // @ts-expect-error - we are omitting the rest of the page props
    props: { filters: { q: '', type: 'people' }, results: [] },
});

describe('SearchPage', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<SearchPage />);
        expect(container).toMatchSnapshot();
    });
});
