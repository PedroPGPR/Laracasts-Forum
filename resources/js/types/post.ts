export type PostTopic = {
    id: number;
    slug: string;
    name: string;
    description: string;
};

export type Post = {
    topic: PostTopic;
    id: number;
    title: string;
    body: string;
    html: string;
    user: {
        id: number;
        name: string;
        email?: string;
    };
    created_at: string | null;
    updated_at: string | null;
    routes: {
        show: string;
    };
};

export type PaginationLinks = {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
};

export type PaginationMeta = {
    current_page: number;
    from: number | null;
    last_page: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    path: string;
    per_page: number;
    to: number | null;
    total: number;
};

export type PostIndexResponse = {
    data: Post[];
    links: PaginationLinks;
    meta: PaginationMeta;
};
