import { Link } from "@inertiajs/react";
import type { SearchResults, User } from "../types";
import { UserCard } from "./UserCard";
import { cn } from "@/lib/utils";
import ChirpCard from "./ChirpCard";
import { useState } from "react";
import { Button } from "./ui/button";
import { router } from "@inertiajs/react";
import { MouseEvent } from "react";

export default function SearchResults({
  strategy,
  results,
}: {
  results: SearchResults<"people" | "chirps">;
  strategy: "chirps" | "people";
}) {
  if (results.length === 0) {
    return <div className="text-lg text-center py-16">No results found</div>;
  }
  if (strategy === "people") {
    return (
      <>
        {(results as SearchResults<"people">).map((user) => (
          <UserResult key={user.id} user={user} />
        ))}
      </>
    );
  } else {
    return (
      <>
        {(results as SearchResults<"chirps">).map((chirp) => (
          <ChirpCard key={chirp.id} chirp={chirp} />
        ))}
      </>
    );
  }
}

function UserResult({ user }: { user: User }) {
  const [following, setFollowing] = useState(user.is_following);

  function changeFollowing(e: MouseEvent) {
    e.preventDefault();
    e.stopPropagation();
    setFollowing(!following);
    router.post(route(following ? "profile.unfollow" : "profile.follow", {
      user: user.id,
    }))
  }
  return (
    <Link
      key={user.id}
      href={`/profile/${user.id}`}
      className="flex px-8 py-4 justify-between hover:bg-accent transition-all border-b"
    >
      <UserCard user={user} disabled />

      <Button
        onClick={changeFollowing}
        className={cn(
          "bg-primary text-primary-foreground rounded-full px-4 py-2 h-10 transition-all hover:brightness-110",
          following && "bg-secondary text-secondary-foreground"
        )}
      >
        {following ? "Unfollow" : "Follow"}
      </Button>
    </Link>
  );
}
