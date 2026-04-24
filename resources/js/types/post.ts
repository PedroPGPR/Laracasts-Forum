type Post = {
    id: number;
    title: string;
    body: string;
};

type PaginationLinks = {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
};

type PaginationMeta = {
    current_page: number;
    from: number;
    last_page: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    path: string;
    per_page: number;
    to: number;
    total: number;
};

export type PostIndexResponse = {
    data: Post[];
    links: PaginationLinks;
    meta: PaginationMeta;
};
