import type { Comment } from "@/types";
import { UserCard } from "./UserCard";
import dayjs from "dayjs";
import { Link, usePage } from "@inertiajs/react";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "./ui/dropdown-menu";
import { Ellipsis } from "lucide-react";
import { Button } from "./ui/button";

export default function Comment({ comment }: { comment: Comment }) {
  const { auth } = usePage().props;
  const isOwner = auth.user.id === comment.user.id;
  return (
    <div className="border-b px-4 py-4 flex flex-col gap-2">
      <div className="flex flex-row justify-between">
        <div className="flex">
          <UserCard user={comment.user} />
          <small className="ml-2 text-sm py-1 text-gray-500">
            {dayjs(comment.created_at).fromNow()}
          </small>
        </div>
        {isOwner && (
          <DropdownMenu>
            <DropdownMenuTrigger asChild onClick={(e) => e.stopPropagation()}>
              <Button className="rounded-full" variant="ghost" size="icon">
                <Ellipsis className="text-gray-600" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent onClick={(e) => e.stopPropagation()}>
              <DropdownMenuItem>
                <Link
                  as="button"
                  href={route("comments.destroy", comment.id)}
                  method="delete"
                  className="w-full text-left"
                >
                  Delete
                </Link>
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        )}
      </div>
      <p className="text-lg px-12">{comment.content}</p>
    </div>
  );
}
