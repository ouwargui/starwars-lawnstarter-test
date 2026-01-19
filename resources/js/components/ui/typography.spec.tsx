import { expect, describe, it } from 'vitest';
import { render } from 'vitest-browser-react';

import { Typography } from './typography';

describe('Typography', () => {
    describe('default rendering', () => {
        it('should render children content', async () => {
            const { getByText } = await render(<Typography>Hello</Typography>);
            expect(getByText('Hello')).toBeInTheDocument();
        });

        it('should render as span by default', async () => {
            const { container } = await render(<Typography>Text</Typography>);
            expect(container.querySelector('span')).toBeInTheDocument();
        });
    });

    describe('as prop (polymorphic)', () => {
        it('should render as h1 when as="h1"', async () => {
            const { container } = await render(<Typography as="h1">Heading</Typography>);
            expect(container.querySelector('h1')).toBeInTheDocument();
        });

        it('should render as p when as="p"', async () => {
            const { container } = await render(<Typography as="p">Paragraph</Typography>);
            expect(container.querySelector('p')).toBeInTheDocument();
        });

        it('should render as div when as="div"', async () => {
            const { container } = await render(<Typography as="div">Block</Typography>);
            expect(container.querySelector('div')).toBeInTheDocument();
        });
    });

    describe('preset prop', () => {
        it('should apply preset classes', async () => {
            const { getByText } = await render(
                <Typography preset="body-default">Body text</Typography>
            );
            const element = getByText('Body text');
            expect(element).toHaveClass('text-sm', 'text-black');
        });

        it('should render without preset classes when preset is not provided', async () => {
            const { getByText } = await render(<Typography>No preset</Typography>);
            const element = getByText('No preset');
            expect(element).not.toHaveClass('text-sm');
        });
    });

    describe('className prop', () => {
        it('should apply custom className', async () => {
            const { getByText } = await render(
                <Typography className="custom-class">Styled</Typography>
            );
            expect(getByText('Styled')).toHaveClass('custom-class');
        });

        it('should merge preset and custom className', async () => {
            const { getByText } = await render(
                <Typography preset="body-default" className="extra-class">
                    Merged
                </Typography>
            );
            const element = getByText('Merged');
            expect(element).toHaveClass('text-sm', 'text-black', 'extra-class');
        });
    });

    describe('additional props', () => {
        it('should pass through props to the element', async () => {
            const { getByText } = await render(
                <Typography data-testid="my-text" className="custom-class" id="my-id">With data</Typography>
            );
            expect(getByText('With data')).toHaveAttribute('data-testid', 'my-text');
            expect(getByText('With data')).toHaveClass('custom-class');
            expect(getByText('With data')).toHaveAttribute('id', 'my-id');
        });
    });
});
