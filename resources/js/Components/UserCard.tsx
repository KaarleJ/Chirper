import { User } from "@/types";
import { UserRound as UserIcon } from "lucide-react";
import { Link } from "@inertiajs/react";

export default function UserCard({ user }: { user: User }) {
  const profilePicture = user.profile_picture;

  return (
    <Link
      href={`/profile/${user.id}`}
      className="flex px-8 py-4 justify-between hover:bg-accent transition-all"
    >
      <div className="flex">
        {profilePicture ? (
          <img
            src={profilePicture}
            alt="Profile Picture"
            className="w-10 h-10 rounded-full"
          />
        ) : (
          <UserIcon />
        )}
        <div className="flex flex-col items-start">
          <p className="text-lg px-2">{user.name}</p>
          <p className="text-sm px-2 text-gray-500 font-thin">
            @{user.username}
          </p>
        </div>
      </div>

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
