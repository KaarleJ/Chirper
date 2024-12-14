import { getFormattedDate } from "@/lib/utils";
import { Chat, Message } from "@/types";
import { useEffect, useState } from "react";

export default function useLiveChat({
  messages,
  chat,
}: {
  messages?: Message[];
  chat?: Chat;
}) {
  const [updatedMessages, setMessages] = useState<Message[]>(messages || []);

  useEffect(() => {
    if (chat) {
      const channel = window.Echo.private(`chat${chat.id}`);
      channel.listen("GotMessage", (e: { message: Message }) => {
        setMessages((prevMessages) => [e.message, ...(prevMessages || [])]);
      });

      return () => {
        channel.stopListening("GotMessage");
        window.Echo.leave(`chat${chat.id}`);
      };
    }
  }, [chat]);

  useEffect(() => {
    if (chat) {
      const unreadMessages = messages?.filter((message) => !message.read_at) || [];
      if (unreadMessages.length > 0) {
        window.axios.post(`/chats/${chat.id}/mark-as-read`);
      }
    }
  }, [chat, messages]);

  const groupedMessages = updatedMessages?.reduce((acc, message) => {
    const messageDate = getFormattedDate(message.created_at);
    if (!acc[messageDate]) {
      acc[messageDate] = [];
    }
    acc[messageDate].push(message);
    return acc;
  }, {} as Record<string, Message[]>);

  return { groupedMessages };
}
