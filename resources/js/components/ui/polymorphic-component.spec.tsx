import { describe, expect, it } from "vitest";
import { render } from "vitest-browser-react";

import { PolymorphicComponent } from "./polymorphic-component";

describe('PolymorphicComponent', () => {
    it('should render as div by default', async () => {
        const { container } = await render(<PolymorphicComponent />);
        expect(container.querySelector('div')).toBeInTheDocument();
    });

    it('should render as the specified element when as prop is provided', async () => {
        const { container } = await render(<PolymorphicComponent as="h1" />);
        expect(container.querySelector('h1')).toBeInTheDocument();
    });

    it('should pass through props to the rendered element', async () => {
        const { container } = await render(<PolymorphicComponent className="test-class" />);
        expect(container.querySelector('div')).toHaveClass('test-class');
    });

    it('should pass through children to the rendered element', async () => {
        const { container } = await render(<PolymorphicComponent>Hello</PolymorphicComponent>);
        expect(container.querySelector('div')).toHaveTextContent('Hello');
    });
});
