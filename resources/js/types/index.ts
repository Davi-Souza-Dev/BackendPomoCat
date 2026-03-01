export * from './auth';
export * from './navigation';
export * from './ui';

import type { Auth } from './auth';

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
};

export interface Timer{
    start: boolean,
    min: number,
    sec: number
}

export interface User{
    username: string,
    email: string,
    photo: string
}

export interface Card{
    id: number,
    title: string,
    image_url: string,
    rarity: string,
}