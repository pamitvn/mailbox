import Blacklisted from '~/types/Models/Blacklisted';
import Wallet from '~/types/Models/Wallet';
import RechargeHistory from '~/types/Models/RechargeHistory';

interface User {
    id: number;
    name: string;
    username: string;
    email: string;
    password?: string;
    balance: number | string;
    is_admin: boolean;
    api_key: string;
    updated_at: string;
    created_at: string;

    blacklisted?: Blacklisted;
    wallet: Wallet;
    recharge_histories?: RechargeHistory[];
}

export default User;
