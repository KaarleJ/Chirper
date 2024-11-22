import { UserIcon } from "lucide-react";
import {
  Dialog,
  DialogContent,
  DialogTitle,
  DialogTrigger,
} from "@/Components/ui/dialog";
import { User } from "@/types";
import { PropsWithChildren, useState } from "react";
import { Separator } from "@/Components/ui/separator";
import { Input } from "./ui/input";
import { Link } from "@inertiajs/react";

export default function CreateChatDialog({
  follows,
  children,
}: PropsWithChildren & { follows: User[] }) {
  const [open, setOpen] = useState(false);
  const [filter, setFilter] = useState("");

  const followings = follows.filter((follow) =>
    follow.name.toLowerCase().includes(filter.toLowerCase())
  );

  return (
    <Dialog open={open} onOpenChange={() => setOpen(!open)}>
      <DialogTrigger asChild>{children}</DialogTrigger>
      <DialogContent>
        <div className="flex flex-col gap-2">
          <DialogTitle>Create a new chat</DialogTitle>
          <Input
            type="text"
            value={filter}
            onChange={(e) => setFilter(e.target.value)}
            placeholder="Search for a user"
            className="my-2"
          />
          <Separator />
          <div className=" gap-2 flex flex-col items-center justify-start h-[20rem] overflow-y-auto">
            {followings.map((follow) => (
              <Link
                href={route("chats.store", { user_id: follow.id })}
                method="post"
                key={follow.id}
                as="button"
                className="flex w-full px-2 py-2 justify-between hover:bg-accent transition-all rounded-md"
                onClick={() => setOpen(false)}
              >
                <div className="flex">
                  {follow.profile_picture ? (
                    <img
                      src={follow.profile_picture}
                      alt="Profile Picture"
                      className="w-10 h-10 rounded-full"
                    />
                  ) : (
                    <UserIcon />
                  )}
                  <div className="flex flex-col items-start">
                    <p className="text-lg px-2">{follow.name}</p>
                    <p className="text-sm px-2 text-gray-500 font-thin">
                      @{follow.username}
                    </p>
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </DialogContent>
    </Dialog>
  );
}
