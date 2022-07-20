import Product from '~/types/Models/Product';
import User from '~/types/Models/User';
import Service from '~/types/Models/Service';

interface Order {
    id: number;
    user_id: number;
    product_id: number;
    price: number;
    quantity: number;
    created_at: string;
    updated_at: string;

    service?: Service;
    user?: User;
    products?: Product[];
}

export default Order;
