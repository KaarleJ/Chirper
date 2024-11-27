import { Chat, User } from "@/types";
import { clsx, type ClassValue } from "clsx";
import { format, isToday, isYesterday, parseISO } from "date-fns";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function resolveChatPartner(auth: { user: User }, chat: Chat) {
  const user =
    chat.user_one.id === auth.user.id ? chat.user_two : chat.user_one;
  return user;
}

export function getFormattedDate(dateString: string) {
  const date = parseISO(dateString);
  if (isToday(date)) return "Today";
  if (isYesterday(date)) return "Yesterday";
  return format(date, "MMMM dd, yyyy");
}
