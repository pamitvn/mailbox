import User from '~/types/Models/User';

interface Blacklisted {
    id: number | string;
    user_id: number | string;
    by_user_id: number | string;
    reason: string;
    duration: string;
    created_at: string;
    updated_at: string;

    user?: User;
    by_user?: User;
}

export default Blacklisted;
