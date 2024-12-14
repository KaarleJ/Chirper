export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string;
  profile_picture?: string;
  username?: string;
  is_following?: boolean;
  is_social: boolean;
}

export interface Chirp {
  id: number;
  user: User;
  message: string;
  liked: boolean;
  likes_count: number;
  created_at: string;
  updated_at: string;
  comments: Comment[];
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>
> = T & {
  auth: {
    user: User;
  };
};

interface Chat {
  id: number;
  user_one: User;
  user_two: User;
  unread_count?: number;
  messages: Message[];
}

interface Message {
  chat_id: number;
  sender_id: number;
  content: string;
  created_at: string;
  updated_at: string;
  read_at: string;
}

interface Comment {
  id: number;
  content: string;
  user: User;
  created_at: string;
  updated_at: string;
}

export type SearchResults<T extends "people" | "chirps"> = T extends "people"
  ? User[]
  : Chirp[];
