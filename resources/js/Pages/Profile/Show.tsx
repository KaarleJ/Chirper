import Chirp from "@/Components/Chirp";
import Header from "@/Components/Header";
import { UserCard } from "@/Components/UserCard";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import type { Chirp as ChirpType, User } from "@/types";
import { Head, Link, usePage } from "@inertiajs/react";

export default function Show({
  user,
  chirps,
  is_following,
  followings,
  followers,
}: {
  chirps: ChirpType[];
  user: User;
  followers: number;
  followings: number;
  is_following: boolean;
}) {
  return (
    <AuthenticatedLayout>
      <Head title={`Profile - ${user.username}`} />
      <Header title={user.username || "Profile"} className="border-b-0" />
      <div className="px-8 flex flex-col border-b">
        <div className="flex justify-between">
          <UserCard user={user} disabled />
          {is_following ? (
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
        </div>
        <div className="flex justify-start">
          <p className="p-4 text-sm text-gray-600">
            <span className="text-black font-extrabold">{followings}</span>{" "}
            Following
          </p>
          <p className="p-4 text-sm text-gray-600">
            <span className="text-black font-extrabold">{followers}</span>{" "}
            Followers
          </p>
        </div>

        <h2 className="text-lg font-semibold py-2">Chirps</h2>
      </div>
      {chirps.map((chirp) => (
        <Chirp chirp={chirp} key={chirp.id} />
      ))}
    </AuthenticatedLayout>
  );
}
