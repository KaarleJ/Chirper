import { Link, usePage } from "@inertiajs/react";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "./ui/dropdown-menu";
import { UserCard } from "./UserCard";

export default function ProfileMenu({ mobile }: { mobile?: boolean }) {
  const { auth } = usePage().props;
  return (
    <DropdownMenu>
      <DropdownMenuTrigger className="text-foreground hover:opacity-50 py-2 transition-all rounded-full">
        <UserCard user={auth.user} disabled />
      </DropdownMenuTrigger>
      <DropdownMenuContent
        side="top"
        align="start"
        alignOffset={mobile ? 0 : 36}
        className="rounded-lg"
      >
        <DropdownMenuItem>
          <Link
            href={route("logout")}
            method="post"
            as="button"
            className="text-lg"
          >
            Logout
          </Link>
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  );
}
