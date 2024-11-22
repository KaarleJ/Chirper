import { cn } from "@/lib/utils";
import { User, Message as MessageType } from "@/types";

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
        "border rounded-lg p-2 shadow-md w-1/2",
        isSender && "self-end"
      )}
    >
      <p>{message.content}</p>
    </div>
  );
}
