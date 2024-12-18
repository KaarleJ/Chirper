import { FormEvent, useState } from "react";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import { Link, useForm, usePage } from "@inertiajs/react";
import { Chirp as ChirpType, PageProps } from "@/types";
import { Button } from "./ui/button";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "./ui/dropdown-menu";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/Components/ui/dialog";
import { Ellipsis } from "lucide-react";
import { MessageCircle as Reply, Heart as Like } from "lucide-react";
import { UserCard } from "./UserCard";
import { router } from "@inertiajs/react";
import { Separator } from "./ui/separator";
import { Textarea } from "./ui/textarea";
import { cn } from "@/lib/utils";

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
        <DropdownMenuTrigger asChild onClick={(e) => e.stopPropagation()}>
          <Button className="rounded-full" variant="ghost" size="icon">
            <Ellipsis className="text-gray-600" />
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent onClick={(e) => e.stopPropagation()}>
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
    const isChirpRoute = window.location.pathname.includes("chirps/");

    if (!editing && !isChirpRoute) {
      router.get(route("chirps.show", { chirp: chirp.id }));
    }
  }

  return (
    <div
      className={cn(
        "px-4 py-4 flex space-x-2 border-b",
        !editing && "hover:cursor-pointer hover:bg-secondary transition-all"
      )}
    >
      <div onClick={navigateToChirp} className="flex-1">
        <div className="flex justify-between items-center">
          <div className="flex items-start">
            <UserCard user={chirp.user} />
            <small className="ml-2 text-sm py-1.5 text-gray-500">
              {dayjs(chirp.created_at).fromNow()}
            </small>
          </div>
          {isOwner && <OwnerActions />}
        </div>
        {editing ? (
          <form onSubmit={submit}>
            <Textarea
              value={data.message}
              onChange={(e) => setData("message", e.target.value)}
              className="mt-4 md:mx-12 md:text-base md:w-10/12"
            />
            <p className="text-destructive">{errors.message}</p>
            <div className="space-x-2">
              <Button>Save</Button>
              <Button
                variant="ghost"
                className="mt-4"
                onClick={() => {
                  setEditing(false);
                  reset();
                  clearErrors();
                }}
              >
                Cancel
              </Button>
            </div>
          </form>
        ) : (
          <p className="mt-4 px-12 text-lg text-gray-900">{chirp.message}</p>
        )}
        <ActionButtons chirp={chirp} auth={auth} />
      </div>
    </div>
  );
}

function ActionButtons({ chirp, auth }: PageProps & { chirp: ChirpType }) {
  const [open, setOpen] = useState(false);
  const [liked, setLiked] = useState(chirp.liked);
  const [likesCount, setLikesCount] = useState(chirp.likes_count);
  const { data, setData, post } = useForm({
    content: "",
  });

  const submit = (e: FormEvent) => {
    e.preventDefault();
    post(route("comments.store", chirp.id), {
      onSuccess: () => setOpen(false),
    });
  };

  const toggleLike = () => {
    setLiked(!liked);
    setLikesCount(liked ? likesCount - 1 : likesCount + 1);
    window.axios
      .post(route("chirps.like", chirp.id))
      .then((response) => {
        const data = response.data;
        setLiked(data.status === "liked");
        setLikesCount(data.likes_count);
      })
      .catch((err) => {
        console.error(err);
        setLiked(chirp.liked);
        setLikesCount(chirp.likes_count);
      });
  };

  return (
    <div
      className="w-min flex justify-start gap-2 pt-4"
      onClick={(e: React.FormEvent) => e.stopPropagation()}
    >
      <Button
        className="rounded-full text-gray-500"
        size="sm"
        variant="ghost"
        onClick={toggleLike}
      >
        <Like
          fill={liked ? "#2563eb" : "background"}
          className={liked ? "text-primary" : ""}
        />
        <span>{likesCount}</span>
      </Button>

      <Dialog open={open} onOpenChange={setOpen}>
        <DialogTrigger asChild>
          <Button
            className="rounded-full text-gray-500"
            size="icon"
            variant="ghost"
            onClick={() => console.log("Reply")}
          >
            <Reply />
          </Button>
        </DialogTrigger>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Reply to {chirp.user.username}</DialogTitle>
            <UserCard user={chirp.user} />
            <p className="px-12">{chirp.message}</p>
          </DialogHeader>
          <Separator className="my-2" />
          <UserCard user={auth.user} />
          <form onSubmit={submit}>
            <Textarea
              className="mb-4"
              placeholder="Reply to this chirp"
              value={data.content}
              onChange={(e) => setData("content", e.target.value)}
            />
            <div className="space-x-2">
              <Button type="submit">Reply</Button>
              <Button
                type="button"
                variant="ghost"
                onClick={() => setOpen(false)}
              >
                Cancel
              </Button>
            </div>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
