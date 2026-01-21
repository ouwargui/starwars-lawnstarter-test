import { describe, it } from "vitest";
import { render } from "vitest-browser-react";

import { Box } from "./box";

describe('Box.Root', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<Box.Root />);
        expect(container).toMatchSnapshot();
    });

    it('should render as the specified element when as prop is provided', async ({ expect }) => {
        const { container } = await render(<Box.Root as="h1" />);
        expect(container.querySelector('h1')).toBeInTheDocument();
    });

    it('should pass through props to the rendered element', async ({ expect }) => {
        const { container } = await render(<Box.Root className="test-class">Hello</Box.Root>);
        expect(container.querySelector('div')).toHaveClass('test-class');
        expect(container.querySelector('div')).toHaveTextContent('Hello');
    });
})

describe('Box.Header', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<Box.Header />);
        expect(container).toMatchSnapshot();
    });

    it('should render as the specified element when as prop is provided', async ({ expect }) => {
        const { container } = await render(<Box.Header as="h1" />);
        expect(container.querySelector('h1')).toBeInTheDocument();
    });

    it('should pass through props to the rendered element', async ({ expect }) => {
        const { container } = await render(<Box.Header className="test-class">Hello</Box.Header>);
        expect(container.querySelector('div')).toHaveClass('test-class');
        expect(container.querySelector('div')).toHaveTextContent('Hello');
    });
});

describe('Box.Content', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<Box.Content />);
        expect(container).toMatchSnapshot();
    });

    it('should render as the specified element when as prop is provided', async ({ expect }) => {
        const { container } = await render(<Box.Content as="h1" />);
        expect(container.querySelector('h1')).toBeInTheDocument();
    });

    it('should pass through props to the rendered element', async ({ expect }) => {
        const { container } = await render(<Box.Content className="test-class">Hello</Box.Content>);
        expect(container.querySelector('div')).toHaveClass('test-class');
        expect(container.querySelector('div')).toHaveTextContent('Hello');
    });
});

describe('Box.Footer', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<Box.Footer />);
        expect(container).toMatchSnapshot();
    });

    it('should render as the specified element when as prop is provided', async ({ expect }) => {
        const { container } = await render(<Box.Footer as="h1" />);
        expect(container.querySelector('h1')).toBeInTheDocument();
    });

    it('should pass through props to the rendered element', async ({ expect }) => {
        const { container } = await render(<Box.Footer className="test-class">Hello</Box.Footer>);
        expect(container.querySelector('div')).toHaveClass('test-class');
        expect(container.querySelector('div')).toHaveTextContent('Hello');
    });
});
