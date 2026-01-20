import { useSearch } from '@/contexts/search-context';
import { search } from '@/routes';

import { Box } from './ui/box';
import { Button } from './ui/button';
import { Typography } from './ui/typography';

const PLACEHOLDERS = {
    people: 'e.g. Chewbacca, Yoda, Boba Fett',
    movies: 'e.g. A New Hope, The Empire Strikes Back',
} as const;

export function SearchBox() {
    const { form } = useSearch();

    function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();

        form.submit(search(), { preserveState: true });
    }

    return (
        <Box.Root as="form" onSubmit={handleSubmit}>
            <Box.Header>
                <Typography
                    as="h2"
                    preset="body-default"
                    className="font-semibold text-primary"
                >
                    What are you searching for?
                </Typography>
            </Box.Header>

            <Box.Content as="fieldset" className="flex-wrap font-bold">
                <label className="flex items-center gap-2">
                    <input
                        type="radio"
                        value="people"
                        name="type"
                        checked={form.data.type === 'people'}
                        onChange={() => form.setData('type', 'people')}
                        className="h-4 w-4 accent-emerald"
                    />
                    <Typography preset="body-default">People</Typography>
                </label>
                <label className="flex items-center gap-2">
                    <input
                        type="radio"
                        value="movies"
                        name="type"
                        checked={form.data.type === 'movies'}
                        onChange={() => form.setData('type', 'movies')}
                        className="h-4 w-4 accent-emerald"
                    />
                    <Typography preset="body-default">Movies</Typography>
                </label>
            </Box.Content>

            <Typography
                as="input"
                preset="body-default"
                type="text"
                name="q"
                placeholder={PLACEHOLDERS[form.data.type]}
                value={form.data.q}
                onChange={(e) => form.setData('q', e.target.value)}
                className="w-full rounded-sm border border-pinkish-gray px-2 py-3 font-bold text-primary placeholder-pinkish-gray transition-colors outline-none focus:border-primary"
            />

            <Button type="submit" disabled={!form.data.q} className="w-full">
                <Typography
                    preset="body-default"
                    className="font-bold text-white uppercase"
                >
                    {form.processing ? 'SEARCHING...' : 'SEARCH'}
                </Typography>
            </Button>
        </Box.Root>
    );
}
