import { Chat, Message, PageProps, User } from "@/types";
import ChatCard from "@/Components/ChatCard";
import ChatScreen from "@/Components/ChatScreen";
import useLiveChats from "@/hooks/useLiveChats";
import { useMediaQuery } from "@/hooks/useMediaQuery";
import ChatsLayout from "@/Layouts/ChatsLayout";
import ChatList from "@/Components/ChatList";

export default function Chats({
  follows,
  chats,
  auth,
}: PageProps & {
  follows: User[];
  chats: Chat[];
}) {
  const { liveChats } = useLiveChats({
    auth,
    initialChats: chats,
  });
  const isDesktop = useMediaQuery("(min-width: 768px)");

  return (
    <ChatsLayout auth={auth} chats={liveChats} follows={follows}>
      {isDesktop ? <ChatScreen auth={auth} /> : <ChatList chats={liveChats} />}
    </ChatsLayout>
  );
}
