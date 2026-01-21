import { InertiaFormProps } from "@inertiajs/react";
import { useState } from "react";
import { describe, it, vi } from "vitest";
import { userEvent } from "vitest/browser";
import { render } from "vitest-browser-react";

import { useSearch } from "@/contexts/search-context";
import { SearchFilters } from "@/interfaces/search";
import { search } from "@/routes";

import { SearchBox } from "./search-box";

vi.mock('@/contexts/search-context');

const submitMock = vi.fn();

function useSearchMock() {
    const [data, setDataState] = useState<SearchFilters>({ q: '', type: 'people' });

    const setData = (key: keyof SearchFilters, value: SearchFilters[keyof SearchFilters]) => {
        setDataState(prev => ({ ...prev, [key]: value }));
    };

    return {
        form: { data, setData, submit: submitMock } as unknown as InertiaFormProps<SearchFilters>,
        results: [],
        filters: undefined,
    }
}

vi.mocked(useSearch).mockImplementation(useSearchMock);

describe('SearchBox', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<SearchBox />);
        expect(container).toMatchSnapshot();
    })

    it('should disable the submit button when the input is empty', async ({ expect }) => {
        const { container } = await render(<SearchBox />);
        expect(container.querySelector('button')).toBeDisabled();
    })

    it('should enable the submit button when the input is not empty', async ({ expect }) => {
        const { getByPlaceholder } = await render(<SearchBox />);
        const input = getByPlaceholder('e.g. Chewbacca, Yoda, Boba Fett');
        await userEvent.type(input, 'test');
        expect(input).toHaveValue('test');
    })

    it('should call the submit function when the form is submitted', async ({ expect }) => {
        const { getByPlaceholder, getByRole } = await render(<SearchBox />);
        const input = getByPlaceholder('e.g. Chewbacca, Yoda, Boba Fett');
        await userEvent.type(input, 'test');
        await userEvent.click(getByRole('button'));
        expect(submitMock).toHaveBeenCalledWith(search(), { preserveState: true });
    })
});
