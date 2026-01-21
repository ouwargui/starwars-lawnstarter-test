import { describe, it } from "vitest";
import { render } from "vitest-browser-react";

import { Button } from "./button";

describe('Button', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<Button />);
        expect(container).toMatchSnapshot();
    });

    it('should render as the specified element when as prop is provided', async ({ expect }) => {
        const { container } = await render(<Button as="h1" />);
        expect(container.querySelector('h1')).toBeInTheDocument();
    });

    it('should pass through props to the rendered element', async ({ expect }) => {
        const { container } = await render(<Button className="test-class">Hello</Button>);
        expect(container.querySelector('button')).toHaveClass('test-class');
        expect(container.querySelector('button')).toHaveTextContent('Hello');
    });
});
