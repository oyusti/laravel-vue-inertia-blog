import type { LucideIcon } from 'lucide-vue-next';
import type { PageProps } from '@inertiajs/core';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface Category {
    id: number;
    name: string;
}

export interface Tag {
    id: number;
    name: string;
}

export interface Post {
    id: number;
    title: string;
    slug:string;
    extract:string;
    content:string;
    image_path:string;
    is_published:boolean;
    user_id:number;
    category_id:number;
}

export interface Message {
    text: string;
    title: string;
    icon: string;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
  }
  
  export interface LaravelPaginator<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
  }

  export interface Permission{
    name:string;
    guard_name:string;
  }
