export async function search(query: string) {
    return new Promise<string[]>((resolve) => {
        setTimeout(() => {
            resolve([query, query, query, query, query]);
        }, 1000);
    });
}
