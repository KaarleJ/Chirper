import { ReactNode } from "react";
import AuthenticatedLayout from "./AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { useMediaQuery } from "@/hooks/useMediaQuery";
import { Chat, PageProps, User } from "@/types";
import ChatCard from "@/Components/ChatCard";
import Header from "@/Components/Header";
import CreateChatDialog from "@/Components/CreateChatDialog";
import { Button } from "@/Components/ui/button";
import { MailPlus as NewMessage } from "lucide-react";
import ChatList from "@/Components/ChatList";

export default function ChatsLayout({
  children,
  follows,
  currentChat,
  auth,
  chats,
}: PageProps & {
  children: ReactNode;
  currentChat?: Chat;
  chats: Chat[];
  follows: User[];
}) {
  const isDesktop = useMediaQuery("(min-width: 768px)");

  return (
    <AuthenticatedLayout hideSearch>
      <Head title="chats" />
      {isDesktop ? (
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
            <ChatList chats={chats} currentChat={currentChat} />
          </div>
          <div className="w-2/3">{children}</div>
        </div>
      ) : (
        <>
          <Header title="Chats" />
          {children}
        </>
      )}
    </AuthenticatedLayout>
  );
}
