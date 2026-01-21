import { PropsWithChildren } from "react";
import { describe, it, vi } from "vitest";
import { render, renderHook } from "vitest-browser-react";

import { SearchProvider, useSearch } from "./search-context";

vi.mock('@inertiajs/react', () => ({
    usePage: () => ({ props: { filters: undefined, results: [] } }),
    useForm: () => ({}),
}));

const Wrapper = ({ children }: PropsWithChildren) => {
    return (
        <SearchProvider>
            {children}
        </SearchProvider>
    )
};

describe('useSearch', () => {
    it('should throw an error when used outside of a SearchProvider', async ({ expect }) => {
        await expect(renderHook(() => useSearch())).rejects.toThrow('useSearch must be used within a SearchProvider');
    });

    it('should return the search context', async ({ expect }) => {
        const { result } = await renderHook(() => useSearch(), { wrapper: Wrapper });
        expect(result.current).toEqual({
            form: {},
            results: [],
            filters: undefined,
        });
    });
});

describe('SearchProvider', () => {
    it('should render the children', async ({ expect }) => {
        const { container } = await render(<SearchProvider><div>Test</div></SearchProvider>);
        expect(container).toMatchSnapshot();
    });
});
