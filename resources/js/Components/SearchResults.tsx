import { Link } from "@inertiajs/react";
import type { SearchResults } from "../types";
import { UserCard } from "./UserCard";
import { cn } from "@/lib/utils";
import ChirpCard from "./ChirpCard";

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
          <Link
            key={user.id}
            href={`/profile/${user.id}`}
            className="flex px-8 py-4 justify-between hover:bg-accent transition-all border-b"
          >
            <UserCard user={user} disabled />

            <Link
              href={route(
                user.is_following ? "profile.unfollow" : "profile.follow",
                {
                  user: user.id,
                }
              )}
              method="post"
              as="button"
              className={cn(
                "bg-primary text-primary-foreground rounded-full px-4 py-2 h-10 transition-all hover:brightness-110",
                user.is_following && "bg-secondary text-secondary-foreground"
              )}
            >
              {user.is_following ? "Unfollow" : "Follow"}
            </Link>
          </Link>
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
