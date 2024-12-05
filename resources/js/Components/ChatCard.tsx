import { cn, resolveChatPartner } from "@/lib/utils";
import { Chat, PageProps } from "@/types";
import { Link } from "@inertiajs/react";
import { UserCard } from "./UserCard";

export default function ChatCard({
  auth,
  chat,
  selected,
}: PageProps & { chat: Chat; selected?: boolean }) {
  const chatPartner = resolveChatPartner(auth, chat);
  const message = chat.messages[0]?.content;
  const unread = chat.unread_count || 0;
  return (
    <Link
      href={route("chats.show", { chat: chat })}
      className={cn("flex border-b p-2", selected && "bg-gray-100")}
    >
      <UserCard user={chatPartner} />
      <p className="self-end px-4 overflow-hidden truncate opacity-50 max-w-[20rem]">
        {message}
      </p>
      {unread > 0 && (
        <div className="bg-primary rounded-full self-center text-center text-white px-3 py-1">
          {unread}
        </div>
      )}
    </Link>
  );
}
