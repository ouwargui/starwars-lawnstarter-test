import { describe, it } from 'vitest';
import { render } from 'vitest-browser-react';

import { DetailsBox } from './details-box';

describe('DetailsBox.Root', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<DetailsBox.Root />);
        expect(container).toMatchSnapshot();
    });

    it('should render the children', async ({ expect }) => {
        const { getByText } = await render(<DetailsBox.Root>Hello</DetailsBox.Root>);
        expect(getByText('Hello')).toBeInTheDocument();
    });
});

describe('DetailsBox.Header', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<DetailsBox.Header />);
        expect(container).toMatchSnapshot();
    });

    it('should render the children', async ({ expect }) => {
        const { getByText } = await render(<DetailsBox.Header>Hello</DetailsBox.Header>);
        expect(getByText('Hello')).toBeInTheDocument();
    });
});

describe('DetailsBox.Content', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<DetailsBox.Content />);
        expect(container).toMatchSnapshot();
    });

    it('should render the children', async ({ expect }) => {
        const { getByText } = await render(
            <DetailsBox.Content>Hello</DetailsBox.Content>,
        );
        expect(getByText('Hello')).toBeInTheDocument();
    });
});

describe('DetailsBox.Aside', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<DetailsBox.Aside />);
        expect(container).toMatchSnapshot();
    });

    it('should render the children', async ({ expect }) => {
        const { getByText } = await render(<DetailsBox.Aside>Hello</DetailsBox.Aside>);
        expect(getByText('Hello')).toBeInTheDocument();
    });
});

describe('DetailsBox.AsideHeader', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<DetailsBox.AsideHeader />);
        expect(container).toMatchSnapshot();
    });

    it('should render the children', async ({ expect }) => {
        const { getByText } = await render(
            <DetailsBox.AsideHeader>Hello</DetailsBox.AsideHeader>,
        );
        expect(getByText('Hello')).toBeInTheDocument();
    });
});

describe('DetailsBox.AsideContent', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<DetailsBox.AsideContent />);
        expect(container).toMatchSnapshot();
    });

    it('should render the children', async ({ expect }) => {
        const { getByText } = await render(
            <DetailsBox.AsideContent>Hello</DetailsBox.AsideContent>,
        );
        expect(getByText('Hello')).toBeInTheDocument();
    });
});

describe('DetailsBox.Footer', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<DetailsBox.Footer />);
        expect(container).toMatchSnapshot();
    });

    it('should render the children', async ({ expect }) => {
        const { getByText } = await render(<DetailsBox.Footer>Hello</DetailsBox.Footer>);
        expect(getByText('Hello')).toBeInTheDocument();
    });
});
