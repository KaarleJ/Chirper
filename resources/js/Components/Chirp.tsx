import { FormEvent, useState } from "react";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import { Link, useForm, usePage } from "@inertiajs/react";
import { Chirp as ChirpType } from "@/types";
import { Button } from "./ui/button";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "./ui/dropdown-menu";
import { Ellipsis } from "lucide-react";
import { MessageCircle as Reply, Heart as Like } from "lucide-react";
import { UserCard } from "./UserCard";
import { router } from "@inertiajs/react";

dayjs.extend(relativeTime);

export default function Chirp({ chirp }: { chirp: ChirpType }) {
  const { auth } = usePage().props;
  const isOwner = auth.user.id === chirp.user.id;

  const [editing, setEditing] = useState(false);

  const { data, setData, patch, clearErrors, reset, errors } = useForm({
    message: chirp.message,
  });

  const submit = (e: FormEvent) => {
    e.preventDefault();
    patch(route("chirps.update", chirp.id), {
      onSuccess: () => setEditing(false),
    });
  };

  function OwnerActions() {
    return (
      <DropdownMenu>
        <DropdownMenuTrigger
          asChild
          onClick={(e: React.FormEvent) => e.stopPropagation()}
        >
          <Button className="rounded-full" variant="ghost" size="icon">
            <Ellipsis className="text-gray-600" />
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent
          onClick={(e: React.FormEvent) => e.stopPropagation()}
        >
          <DropdownMenuItem>
            <button
              onClick={() => setEditing(true)}
              className="w-full text-left"
            >
              Edit
            </button>
          </DropdownMenuItem>
          <DropdownMenuItem>
            <Link
              as="button"
              href={route("chirps.destroy", chirp.id)}
              method="delete"
              className="w-full text-left"
            >
              Delete
            </Link>
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    );
  }

  function navigateToChirp() {
    const isChirpRoute = window.location.pathname.includes(
      route("chirps.show", { chirp: chirp.id })
    );

    if (!editing && !isChirpRoute) {
      router.get(route("chirps.show", { chirp: chirp.id }));
    }
  }

  return (
    <div className="px-8 py-4 flex space-x-2 border-b hover:cursor-pointer hover:bg-accent transition-all">
      <div onClick={navigateToChirp} className="flex-1">
        <div className="flex justify-between items-center">
          <div className="flex items-start">
            <UserCard user={chirp.user} />
            <small className="ml-2 text-sm py-1.5 text-gray-500">
              {dayjs(chirp.created_at).fromNow()}
            </small>
            {chirp.created_at !== chirp.updated_at && (
              <small className="text-sm text-gray-600"> &middot; edited</small>
            )}
          </div>
          {isOwner && <OwnerActions />}
        </div>
        {editing ? (
          <form onSubmit={submit}>
            <textarea
              value={data.message}
              onChange={(e) => setData("message", e.target.value)}
              className="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            />
            <p className="text-destructive">{errors.message}</p>
            <div className="space-x-2">
              <Button>Save</Button>
              <button
                className="mt-4"
                onClick={() => {
                  setEditing(false);
                  reset();
                  clearErrors();
                }}
              >
                Cancel
              </button>
            </div>
          </form>
        ) : (
          <p className="mt-4 text-lg text-gray-900">{chirp.message}</p>
        )}
        <ActionButtons />
      </div>
    </div>
  );
}

function ActionButtons() {
  return (
    <div
      className="w-full flex justify-start gap-10 pt-4"
      onClick={(e: React.FormEvent) => e.stopPropagation()}
    >
      <Button
        className="rounded-full text-gray-500"
        size="icon"
        variant="ghost"
        onClick={() => console.log("Reply")}
      >
        <Reply />
      </Button>
      <Button
        className="rounded-full text-gray-500"
        size="icon"
        variant="ghost"
        onClick={() => console.log("Like")}
      >
        <Like />
      </Button>
    </div>
  );
}
