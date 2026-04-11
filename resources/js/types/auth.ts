export type User = {
    username: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    // user: User;
    username: string,
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
