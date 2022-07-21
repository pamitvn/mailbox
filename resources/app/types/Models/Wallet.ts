import User from '~/types/Models/User';

interface Wallet {
    uuid: string;
    name: string;
    slug: string;
    balance: number;
    decimal_places: number;
    holder_id: number;
    holder_type: string;
    meta: any[];

    holder: User;
}

export default Wallet;
