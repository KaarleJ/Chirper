import { cn } from "@/lib/utils";
import { Chat, PageProps } from "@/types";
import { Link } from "@inertiajs/react";
import { UserIcon } from "lucide-react";

export default function ChatCard({
  auth,
  chat,
  selected,
}: PageProps & { chat: Chat; selected: boolean }) {
  const user =
    chat.user_one.id === auth.user.id ? chat.user_two : chat.user_one;
  const message = chat.messages[0]?.content;
  return (
    <Link
      href={route("chats.show", { chat: chat })}
      className={cn("flex border-b p-2", selected && "bg-gray-100")}
    >
      {user.profile_picture ? (
        <img
          src={user.profile_picture}
          alt="Profile Picture"
          className="w-10 h-10 rounded-full"
        />
      ) : (
        <UserIcon />
      )}
      <div className="flex flex-col items-start">
        <p className="text-lg px-2">{user.name}</p>
        <p className="text-sm px-2 text-gray-500 font-thin">@{user.username}</p>
      </div>
      <p className="self-end px-4 overflow-hidden truncate opacity-50 max-w-[20rem]">
        {message}
      </p>
    </Link>
  );
}
