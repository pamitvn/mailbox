import User from '~/types/Models/User';

interface Blacklisted {
    id: number;
    user_id: number;
    by_user_id: number;
    reason: string;
    duration: string;
    created_at: string;
    updated_at: string;

    user?: User;
    byUser?: User;
}

export default Blacklisted;
