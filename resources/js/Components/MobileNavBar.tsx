import NavLink from "./NavLink";
import {
  NavigationMenu,
  NavigationMenuItem,
  NavigationMenuList,
} from "./ui/navigation-menu";
import { House, Mail, User, Search, CirclePlus } from "lucide-react";

export default function MobileNavBar() {
  return (
    <NavigationMenu className="absolute bottom-0 left-0 h-20 w-screen border-t">
      <NavigationMenuList className="w-screen px-8 py-4 flex items-center justify-between">
        <NavigationMenuItem>
          <NavLink href={route("home")}>
            <House size={36} />
          </NavLink>
        </NavigationMenuItem>

        <NavigationMenuItem>
          <NavLink href={route("search.index")}>
            <Search size={36} />
          </NavLink>
        </NavigationMenuItem>

        <NavigationMenuItem>
          <NavLink href={route("home")} className="text-primary">
            <CirclePlus size={42} />
          </NavLink>
        </NavigationMenuItem>

        <NavigationMenuItem>
          <NavLink href={route("chats.index")}>
            <Mail size={36} />
          </NavLink>
        </NavigationMenuItem>

        <NavigationMenuItem>
          <NavLink href={route("profile.edit")}>
            <User size={36} />
          </NavLink>
        </NavigationMenuItem>
      </NavigationMenuList>
    </NavigationMenu>
  );
}
