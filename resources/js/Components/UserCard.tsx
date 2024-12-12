import { User } from "@/types";
import { UserRound as UserIcon } from "lucide-react";
import { router } from "@inertiajs/react";
import { cn } from "@/lib/utils";

export function UserCard({
  user,
  disabled = false,
}: {
  user: User;
  disabled?: boolean;
}) {
  function navigateToProfile(e: React.FormEvent) {
    e.stopPropagation();
    if (!disabled) {
      router.get(route("profile.show", { user: user.id }));
    }
  }
  return (
    <div
      className={cn(
        "flex items-start rounded-full w-max text-left",
        !disabled && "hover:bg-secondary hover:cursor-pointer transition-all"
      )}
      onClick={navigateToProfile}
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
      <div className="flex flex-col">
        <p className="text-lg px-2">{user.name}</p>
        <p className="text-md px-2 text-gray-500 font-thin">@{user.username}</p>
      </div>
    </div>
  );
}
