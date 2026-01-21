import { InertiaFormProps } from "@inertiajs/react";
import { beforeEach, describe, it, vi } from "vitest";
import { render } from "vitest-browser-react";

import { useSearch } from "@/contexts/search-context";
import { SearchFilters } from "@/interfaces/search";

import { ResultsBox } from "./results-box";

vi.mock('@/contexts/search-context');

const inactiveSearchMock: ReturnType<typeof useSearch> = {
    form: { processing: false, hasErrors: false } as InertiaFormProps<SearchFilters>,
    results: [],
    filters: undefined,
};

const searchProcessingMock: ReturnType<typeof useSearch> = {
    form: { processing: true, hasErrors: false } as InertiaFormProps<SearchFilters>,
    results: [],
    filters: { q: '', type: 'people' },
};

const emptyResultsMock: ReturnType<typeof useSearch> = {
    form: { processing: false, hasErrors: false } as InertiaFormProps<SearchFilters>,
    results: [],
    filters: { q: '', type: 'people' },
};

const resultsMock: ReturnType<typeof useSearch> = {
    form: { processing: false, hasErrors: false } as InertiaFormProps<SearchFilters>,
    results: [{ id: '1', name: 'Luke Skywalker' }],
    filters: { q: 'Luke', type: 'people' },
};

const errorSearchMock: ReturnType<typeof useSearch> = {
    form: { processing: false, hasErrors: true } as InertiaFormProps<SearchFilters>,
    results: [],
    filters: { q: 'Luke', type: 'people' },
};

describe('ResultsBox', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('should match snapshot when the search is inactive', async ({ expect }) => {
        vi.mocked(useSearch).mockReturnValue(inactiveSearchMock);
        const { container } = await render(<ResultsBox />);
        expect(container).toMatchSnapshot();
    })

    it('should match snapshot when the search is processing', async ({ expect }) => {
        vi.mocked(useSearch).mockReturnValue(searchProcessingMock);
        const { container } = await render(<ResultsBox />);
        expect(container).toMatchSnapshot();
    })

    it('should match snapshot when the search has empty results', async ({ expect }) => {
        vi.mocked(useSearch).mockReturnValue(emptyResultsMock);
        const { container } = await render(<ResultsBox />);
        expect(container).toMatchSnapshot();
    })

    it('should match snapshot when the search has results', async ({ expect }) => {
        vi.mocked(useSearch).mockReturnValue(resultsMock);
        const { container } = await render(<ResultsBox />);
        expect(container).toMatchSnapshot();
    })

    it('should match snapshot when the search has an error', async ({ expect }) => {
        vi.mocked(useSearch).mockReturnValue(errorSearchMock);
        const { container } = await render(<ResultsBox />);
        expect(container).toMatchSnapshot();
    })
});
