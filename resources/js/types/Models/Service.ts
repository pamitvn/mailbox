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
    clean_after: number;
    created_at: string;
    updated_at: string;
}

export default Service;
