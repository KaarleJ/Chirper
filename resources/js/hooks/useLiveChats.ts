import { Chat, Message, PageProps } from "@/types";
import { useEffect, useState } from "react";

export default function useLiveChats({
  auth,
  chat,
  initialChats,
}: PageProps & { chat?: Chat; initialChats: Chat[] }) {
  const [liveChats, setLiveChats] = useState(initialChats);

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
              unread_count: chat.unread_count ? chat.unread_count + 1 : 1,
            };
          }
          return chat;
        });
      });
    });

    return () => {
      userChannel.stopListening("GotMessage");
      window.Echo.leave(`user.${auth.user.id}`);
    };
  }, [auth.user.id]);

  return { liveChats };
}
