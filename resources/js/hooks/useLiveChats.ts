import { Chat, Message, PageProps } from "@/types";
import { useEffect, useState } from "react";

export default function useLiveChats({
  auth,
  chat,
  initialChats,
}: PageProps & { chat?: Chat; initialChats: Chat[] }) {
  const [liveChats, setLiveChats] = useState(initialChats);
  const [unreadChats, setUnreadChats] = useState<Record<string, boolean>>({});

  useEffect(() => {
    const userChannel = window.Echo.private(`user${auth.user.id}`);

    userChannel.listen("GotMessage", (e: { message: Message }) => {
      const newMessage = e.message;
      const chatId = e.message.chat_id;

      setLiveChats((prevChats) => {
        return prevChats.map((chat) => {
          if (chat.id === chatId) {
            return {
              ...chat,
              messages: [newMessage, ...chat.messages],
            };
          }
          return chat;
        });
      });

      setUnreadChats((prev) => ({
        ...prev,
        [chatId]: true,
      }));
    });

    return () => {
      userChannel.stopListening("GotMessage");
      window.Echo.leave(`user.${auth.user.id}`);
    };
  }, [auth.user.id]);

  useEffect(() => {
    if (chat) {
      setUnreadChats((prev) => {
        const updated = { ...prev };
        delete updated[chat.id];
        return updated;
      });
    }
  }, [chat]);

  return { unreadChats, liveChats };
}
