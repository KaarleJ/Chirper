import ApplicationLogo from "./ApplicationLogo";
import NavLink from "./NavLink";
import { House, Mail, User } from "lucide-react";
import {
  NavigationMenu,
  NavigationMenuItem,
  NavigationMenuList,
} from "./ui/navigation-menu";
import ProfileMenu from "./ProfileMenu";

export default function SideNav() {
  return (
    <div className="min-h-screen px-24 py-8 border-r">
      <NavigationMenu orientation="vertical">
        <NavigationMenuList className="flex-col items-start justify-start gap-8 w-[10rem]">
          <NavigationMenuItem className="mb-10">
            <NavLink href={"/"}>
              <ApplicationLogo className="h-14 w-auto fill-current" />
            </NavLink>
          </NavigationMenuItem>

          <NavigationMenuItem>
            <NavLink href={route("home")}>
              <House size={24} /> Home
            </NavLink>
          </NavigationMenuItem>

          <NavigationMenuItem>
            <NavLink href={route("profile.edit")}>
              <User />
              Profile
            </NavLink>
          </NavigationMenuItem>

          <NavigationMenuItem>
            <NavLink href={route("chats.index")}>
              <Mail />
              Chats
            </NavLink>
          </NavigationMenuItem>

          <NavigationMenuItem>
            <NavLink
              href={route("chirps.index")}
              as="button"
              className="text-lg bg-primary text-primary-foreground rounded-full px-8 py-2 mt-8 transition-all"
            >
              Chirp
            </NavLink>
          </NavigationMenuItem>

          <NavigationMenuItem>
            <ProfileMenu />
          </NavigationMenuItem>
        </NavigationMenuList>
      </NavigationMenu>
    </div>
  );
}
