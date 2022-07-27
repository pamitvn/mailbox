interface Product {
    id: number;
    service_id: number;
    payload: string | null;
    status: number;
    created_at: string;
    updated_at: string;
}

export default Product;
