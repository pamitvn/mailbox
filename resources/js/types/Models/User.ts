interface User {
    name: string;
    username: string;
    email: string;
    password?: string;
    balance: number | string;
    is_admin: boolean;
    api_key: string;
    updated_at: string;
    created_at: string;
}

export default User;
