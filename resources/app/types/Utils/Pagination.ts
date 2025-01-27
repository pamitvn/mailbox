export interface Link {
    active: boolean;
    label: string;
    url: string | null;
}

export interface Type<RecordType = object> {
    total: number;
    per_page: number;
    current_page: number;
    last_page: number;
    first_page_url: string | null;
    last_page_url: string | null;
    next_page_url: string | null;
    prev_page_url: string | null;
    path: string;
    from: number;
    to: number;
    data: Array<RecordType>;
    links: Link[];
}

export interface Cursor<RecordType = object> {
    data: Array<RecordType>;
    next_cursor: string | null;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_cursor: string | null;
    prev_page_url: string | null;
}

type Pagination = Type

export default Pagination;
