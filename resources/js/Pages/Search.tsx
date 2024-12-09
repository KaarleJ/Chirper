import Header from "@/Components/Header";
import { Button } from "@/Components/ui/button";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, usePage, useForm, Link } from "@inertiajs/react";
import { Input } from "@/Components/ui/input";
import { FormEvent } from "react";
import { cn } from "@/lib/utils";
import { Chirp, User } from "@/types";
import { UserCard } from "@/Components/UserCard";
import ChirpCard from "@/Components/ChirpCard";

type SearchResults<T extends "people" | "chirps"> = T extends "people"
  ? User[]
  : Chirp[];

export default function Search() {
  const { results, query, strategy } = usePage().props;

  const { data, setData, get } = useForm({
    query: (query as string | undefined) || "",
    strategy: (strategy as string | undefined) || "",
  });

  function submit(e: FormEvent) {
    e.preventDefault();
    get(route("search.index"));
  }

  function changeStrategy(e: FormEvent) {
    e.preventDefault();
    const newStrategy = strategy === "people" ? "chirps" : "people";
    setData("strategy", newStrategy);
    get(route("search.index", { strategy: newStrategy }), {
      preserveState: true,
    });
  }

  const resultsData = results as SearchResults<"people" | "chirps">;

  return (
    <AuthenticatedLayout>
      <Head title="Search" />
      <Header title="Search" className="border-b-0 pb-4" />
      <div className="border-b">
        <form onSubmit={submit}>
          <div className="px-8 py-4 w-[25rem] flex gap-2">
            <Input
              className="rounded-full pl-4 w-full"
              placeholder="search"
              value={data.query}
              onChange={(e) => setData("query", e.target.value)}
            />
          </div>
        </form>
        <form onSubmit={changeStrategy}>
          <input type="hidden" name="strategy" value={data.strategy} />
          <div className="flex px-4 gap-4">
            <Button
              className={cn(
                "text-lg relative",
                strategy === "people" && underline
              )}
              variant="ghost"
            >
              People
            </Button>
            <Button
              className={cn(
                "text-lg relative",
                strategy === "chirps" && underline
              )}
              variant="ghost"
            >
              Chirps
            </Button>
          </div>
        </form>
      </div>

      {strategy === "people"
        ? (resultsData as User[]).map((user) => (
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
          ))
        : (resultsData as Chirp[]).map((chirp) => (
            <ChirpCard key={chirp.id} chirp={chirp} />
          ))}
    </AuthenticatedLayout>
  );
}

const underline =
  "after:content-[''] after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-14 after:h-1 after:bg-primary after:rounded-full";
