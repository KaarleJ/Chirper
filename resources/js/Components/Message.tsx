import { cn } from "@/lib/utils";
import { User, Message as MessageType } from "@/types";
import { format } from "date-fns";

export default function Message({
  message,
  auth,
  chatPartner,
}: {
  message: MessageType;
  auth: { user: User };
  chatPartner: User;
}) {
  const isSender = message.sender_id === auth.user.id;
  return (
    <div
      className={cn(
        "border rounded-lg px-4 py-2 shadow-md w-1/2",
        isSender && "self-end"
      )}
    >
      <p>{message.content}</p>
      <p className="text-right text-sm text-gray-400">
        {format(message.created_at, "HH.mm")}
      </p>
    </div>
  );
}
