import Header from "@/Components/Header";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Chat, Message, PageProps, User } from "@/types";
import { Head } from "@inertiajs/react";
import CreateChatDialog from "@/Components/CreateChatDialog";
import ChatCard from "@/Components/ChatCard";

export default function Chats({
  follows,
  chats,
  auth,
  currentChat,
}: PageProps & {
  follows: User[];
  chats: Chat[];
  messages: Message[];
  currentChat?: Chat;
}) {
  console.log(chats);
  return (
    <AuthenticatedLayout>
      <Head title="Chats" />
      <div className="flex items-center justify-between border-b">
        <Header title="Chats" className="border-b-0" />
        <CreateChatDialog follows={follows} />
      </div>

      <div>
        {chats.map((chat) => (
          <ChatCard
            key={chat.id}
            chat={chat}
            auth={auth}
            selected={currentChat?.id === chat.id}
          />
        ))}
      </div>
    </AuthenticatedLayout>
  );
}
