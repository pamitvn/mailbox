import Product from '~/types/Models/Product';
import User from '~/types/Models/User';

interface Order {
    id: number;
    user_id: number;
    product_id: number;
    price: number;
    created_at: string;
    updated_at: string;

    product?: Product;
    user?: User;
}

export default Order;
