import { Link, usePage } from "@inertiajs/react";
import { Button } from "./ui/button";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "./ui/dropdown-menu";

export default function ProfileMenu() {
  const { auth } = usePage().props;
  const profilePicture = auth.user.profile_picture;
  return (
    <DropdownMenu>
      <DropdownMenuTrigger className="flex items-end text-foreground text-xl font-semibold hover:bg-accent py-2 transition-all text-nowrap rounded-full w-max">
        <img
          src={profilePicture}
          alt="Profile Picture"
          className="w-10 h-10 rounded-full"
        />{" "}
        <p className="text-lg px-2">{auth.user.name}</p>
      </DropdownMenuTrigger>
      <DropdownMenuContent side="top" align="start" className="rounded-lg">
        <DropdownMenuItem>
          <Link href={route("logout")} method="post" as="button" className="text-lg">
            Logout
          </Link>
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  );
}
