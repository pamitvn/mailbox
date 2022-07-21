import User from '~/types/Models/User';
import Bank from '~/types/Models/Bank';

interface RechargeHistory {
    id: number;
    user_id: number;
    bank_id: number;
    amount: string;
    before_transaction: string;
    after_transaction: string;
    note: string;
    updated_at: string;
    created_at: string;

    user?: User;
    bank?: Bank;
}

export default RechargeHistory;
