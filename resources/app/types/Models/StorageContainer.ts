interface StorageContainer {
    id: number;
    storage_id: number;
    payload: object | any[] | null;
    status: number;
    created_at: string;
    updated_at: string;
}

export default StorageContainer;
