interface Service {
    id: number;
    name: string;
    slug: string;
    lifetime: string;
    feature_image: string;
    price: number;
    pop3: boolean;
    imap: boolean;
    visible: boolean;
    is_local: boolean;
    clean_after: number;
    extras: {
        [key: string]: any
    };
    created_at: string;
    updated_at: string;
}

export default Service;
