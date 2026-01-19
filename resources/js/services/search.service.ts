export async function search(query: string, type: 'people' | 'movies') {
    return new Promise<string[]>((resolve) => {
        setTimeout(() => {
            resolve([query, query, type, query, type]);
        }, 1000);
    });
}
