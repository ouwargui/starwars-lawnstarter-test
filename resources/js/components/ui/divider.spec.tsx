import { describe, it } from "vitest";
import { render } from "vitest-browser-react";

import { Divider } from "./divider";

describe('Divider', () => {
    it('match snapshot', async ({ expect }) => {
        const { container } = await render(<Divider />);
        expect(container).toMatchSnapshot();
    });
});
