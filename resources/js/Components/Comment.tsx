import type { Comment } from "@/types";
import { UserCard } from "./UserCard";
import dayjs from "dayjs";

export default function Comment({ comment }: { comment: Comment }) {
  return (
    <div className="border-b px-4 py-4 flex flex-col gap-2">
      <div className="flex">
        <UserCard user={comment.user} />
        <small className="ml-2 text-sm py-1 text-gray-500">
          {dayjs(comment.created_at).fromNow()}
        </small>
      </div>

      <p className="text-lg px-12">{comment.content}</p>
    </div>
  );
}
