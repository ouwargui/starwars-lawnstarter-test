import { ComponentPropsWithoutRef } from "react";
import { describe, it, vi } from "vitest";
import { render } from "vitest-browser-react";

import { PageLayout } from "./page-layout";

vi.mock('@inertiajs/react', () => ({
    Link: (props: ComponentPropsWithoutRef<'a'>) => <a {...props} />,
}));

describe('PageLayout', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<PageLayout />);
        expect(container).toMatchSnapshot();
    })

    it('should render the children', async ({ expect }) => {
        const { getByText } = await render(<PageLayout>Hello</PageLayout>)
        expect(getByText('Hello')).toBeInTheDocument();
    })
});
