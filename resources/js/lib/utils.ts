import { Chat, User } from "@/types";
import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function resolveChatPartner(auth: { user: User }, chat: Chat) {
  const user =
    chat.user_one.id === auth.user.id ? chat.user_two : chat.user_one;
  return user;
}
