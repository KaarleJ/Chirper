import Header from "@/Components/Header";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Chat, Message, PageProps, User } from "@/types";
import { Head } from "@inertiajs/react";
import CreateChatDialog from "@/Components/CreateChatDialog";
import ChatCard from "@/Components/ChatCard";
import ChatScreen from "@/Components/ChatScreen";
import { Button } from "@/Components/ui/button";
import { MailPlus as NewMessage } from "lucide-react";
import useLiveChats from "@/hooks/useLiveChats";
import { useMediaQuery } from "@/hooks/useMediaQuery";
import ChatsLayout from "@/Layouts/ChatsLayout";

export default function Chats({
  follows,
  chats,
  auth,
  messages,
  currentChat,
}: PageProps & {
  follows: User[];
  chats: Chat[];
  messages: Message[];
  currentChat?: Chat;
}) {
  const { liveChats } = useLiveChats({
    auth,
    chat: currentChat,
    initialChats: chats,
  });
  const isDesktop = useMediaQuery("(min-width: 768px)");

  function Children() {
    if (currentChat) {
      return <ChatScreen chat={currentChat} auth={auth} messages={messages} />;
    } else if (!isDesktop) {
      return (
        <div className="h-full">
          {liveChats.map((chat) => (
            <ChatCard key={chat.id} chat={chat} auth={auth} />
          ))}
        </div>
      );
    }
  }

  return (
    <ChatsLayout
      auth={auth}
      chats={liveChats}
      follows={follows}
      currentChat={currentChat}
    >
      <Children />
    </ChatsLayout>
  );
}
