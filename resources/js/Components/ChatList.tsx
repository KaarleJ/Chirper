import { Chat } from "@/types";
import ChatCard from "./ChatCard";
import { usePage } from "@inertiajs/react";

export default function ChatList({
  chats,
  currentChat,
}: {
  chats: Chat[];
  currentChat?: Chat;
}) {
  const { auth } = usePage().props;
  return (
    <div className="h-full">
      {chats.length === 0 ? (
        <p className="px-8 py-6 text-gray-500">No chats yet</p>
      ) : (
        chats.map((chat) => (
          <ChatCard
            key={chat.id}
            chat={chat}
            auth={auth}
            selected={currentChat?.id === chat.id}
          />
        ))
      )}
    </div>
  );
}
