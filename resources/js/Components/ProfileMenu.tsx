import { Link, usePage } from "@inertiajs/react";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "./ui/dropdown-menu";

export default function ProfileMenu() {
  const { auth } = usePage().props;
  const profilePicture = auth.user.profile_picture;
  return (
    <DropdownMenu>
      <DropdownMenuTrigger className="flex items-center text-foreground text-xl font-semibold hover:bg-accent py-2 transition-all text-nowrap rounded-full w-max">
        <img
          src={profilePicture}
          alt="Profile Picture"
          className="w-10 h-10 rounded-full"
        />{" "}
        <div className="flex flex-col items-start">
          <p className="text-lg px-2">{auth.user.name}</p>
          <p className="text-sm px-2 text-gray-500 font-thin">
            @{auth.user.username}
          </p>
        </div>
      </DropdownMenuTrigger>
      <DropdownMenuContent
        side="top"
        align="start"
        alignOffset={36}
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
