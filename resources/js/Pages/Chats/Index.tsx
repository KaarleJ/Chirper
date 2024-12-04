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
  return (
    <AuthenticatedLayout hideSearch className="border-r">
      <Head title="Chats" />
      <div className="flex flex-row h-full">
        <div className="flex flex-col w-1/3 border-r">
          <div className="flex items-center justify-between border-b h-[6rem]">
            <Header title="Chats" className="border-b-0 w-min" />
            <CreateChatDialog follows={follows}>
              <Button className="mr-12 rounded-full p-2" size="icon">
                <NewMessage />
              </Button>
            </CreateChatDialog>
          </div>

          <div className="h-full">
            {liveChats.map((chat) => (
              <ChatCard
                key={chat.id}
                chat={chat}
                auth={auth}
                selected={currentChat?.id === chat.id}
              />
            ))}
          </div>
        </div>

        <div className="w-2/3">
          <ChatScreen chat={currentChat} auth={auth} messages={messages} />
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
