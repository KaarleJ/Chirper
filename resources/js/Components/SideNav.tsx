import ApplicationLogo from "./ApplicationLogo";
import NavLink from "./NavLink";
import { Button } from "./ui/button";
import { House, Bell } from "lucide-react";
import {
  NavigationMenu,
  NavigationMenuItem,
  NavigationMenuList,
} from "./ui/navigation-menu";

export default function SideNav() {
  return (
    <div className="min-h-screen p-16">
      <NavigationMenu orientation="vertical">
        <NavigationMenuList className="flex-col items-start justify-start gap-6 w-[10rem]">
          <NavigationMenuItem className="mb-10">
            <NavLink href="/">
              <ApplicationLogo className="h-16 w-auto fill-current text-gray-800" />
            </NavLink>
          </NavigationMenuItem>
          <NavigationMenuItem>
            <NavLink href="/dashboard">
              <House size={24} /> Dashboard
            </NavLink>
          </NavigationMenuItem>
          <NavigationMenuItem>
            <NavLink href="/chirps"><Bell size={24} /> Chirps</NavLink>
          </NavigationMenuItem>
          <NavigationMenuItem>
            <Button asChild className="text-xl font-semibold rounded-full px-8 mt-8">
              <NavLink href="/chirps">Chirp</NavLink>
            </Button>
          </NavigationMenuItem>
        </NavigationMenuList>
      </NavigationMenu>
    </div>
  );
}
