import { Chat, Message, PageProps, User } from "@/types";
import ChatScreen from "@/Components/ChatScreen";
import useLiveChats from "@/hooks/useLiveChats";
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
  return (
    <ChatsLayout
      auth={auth}
      chats={liveChats}
      follows={follows}
      currentChat={currentChat}
    >
      {<ChatScreen chat={currentChat} auth={auth} messages={messages} />}
    </ChatsLayout>
  );
}
