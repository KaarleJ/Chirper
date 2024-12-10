import { User } from "@/types";
import { UserRound as UserIcon } from "lucide-react";
import { Link } from "@inertiajs/react";
import { cn } from "@/lib/utils";

export function UserCard({ user }: { user: User }) {
  return (
    <div
      className="flex items-start"
      onClick={(e: React.FormEvent) => e.stopPropagation()}
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

export function UserCardLink({ user }: { user: User }) {
  return (
    <Link
      href={`/profile/${user.id}`}
      className="flex px-8 py-4 justify-between hover:bg-accent transition-all"
    >
      <UserCard user={user} />

      {user.is_following ? (
        <Link
          href={route("profile.unfollow", { user: user.id })}
          method="post"
          as="button"
          className="border bg-secondary text-secondary-foreground rounded-full px-4 py-2 h-10 transition-all hover:brightness-110"
        >
          Unfollow
        </Link>
      ) : (
        <Link
          href={route("profile.follow", { user: user.id })}
          method="post"
          as="button"
          className="bg-primary text-primary-foreground rounded-full px-4 py-2 h-10 transition-all hover:brightness-110"
        >
          Follow
        </Link>
      )}
    </Link>
  );
}
