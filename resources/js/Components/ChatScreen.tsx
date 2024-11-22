import { Chat, Message as MessageType, User } from "@/types";
import CreateChatDialog from "./CreateChatDialog";
import { useForm, usePage } from "@inertiajs/react";
import { Button } from "./ui/button";
import { resolveChatPartner } from "@/lib/utils";
import { UserCard } from "./UserCard";
import Message from "./Message";
import { FormEvent } from "react";
import { Input } from "./ui/input";
import { Send } from "lucide-react";

export default function ChatScreen({
  chat,
  messages,
  auth,
}: {
  chat?: Chat;
  messages?: MessageType[];
  auth: { user: User };
}) {
  const { props } = usePage();
  const { data, setData, post, clearErrors, reset, errors } = useForm({
    content: "",
  });
  const follows = props.follows as User[];

  if (!chat) {
    return NoChat(follows);
  }

  const chatPartner = resolveChatPartner(auth, chat);

  function submit(e: FormEvent) {
    e.preventDefault();
    post(route("messages.store", { chat: chat?.id }), {
      onSuccess: () => {
        reset();
        clearErrors();
      },
    });
  }

  return (
    <div className="h-full flex flex-col justify-start">
      <div className="h-[6.5rem] border-b flex items-center px-4">
        <UserCard user={chatPartner} />
      </div>
      <div className="p-4 relative h-full">
        <div className="flex flex-col gap-4">
          {messages?.map((message) => (
            <Message
              key={message.chat_id + message.sender_id + message.created_at}
              message={message}
              auth={auth}
              chatPartner={chatPartner}
            />
          ))}
        </div>
        <form
          onSubmit={submit}
          className="absolute bottom-10 left-0 p-4 w-full flex gap-2"
        >
          <Input
            type="text"
            value={data.content}
            onChange={(e) => setData("content", e.target.value)}
            placeholder="Type a message..."
            className="w-full p-2 border rounded-lg h-"
          />
          <Button type="submit" className="rounded-lg pl-4 pr-5 flex">
            <Send />
          </Button>
        </form>
      </div>
    </div>
  );
}

function NoChat(follows: User[]) {
  return (
    <div className="flex gap-2 flex-col justify-center items-start h-2/3 px-44">
      <h3 className="text-2xl font-semibold">
        Select a chat to start messaging
      </h3>
      <p>
        Choose from your existing chats, create a new one, or just wait for a
        new chat.
      </p>
      <CreateChatDialog follows={follows}>
        <Button className="my-4 rounded-full text-lg">New Chat</Button>
      </CreateChatDialog>
    </div>
  );
}
