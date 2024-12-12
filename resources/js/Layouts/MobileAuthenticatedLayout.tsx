import { cn } from "@/lib/utils";
import { ReactNode } from "react";
import NavLink from "../Components/NavLink";
import {
  NavigationMenu,
  NavigationMenuItem,
  NavigationMenuList,
} from "../Components/ui/navigation-menu";
import { House, Mail, User, Search, CirclePlus } from "lucide-react";
import ApplicationLogo from "../Components/ApplicationLogo";
import ProfileMenu from "../Components/ProfileMenu";

export default function MobileAuthenticatedLayout({
  children,
  className,
}: {
  className?: string;
  children: ReactNode;
}) {
  return (
    <div className="flex flex-col item-start">
      <div className="absolute top-0 left-0 h-16 px-8 py-4 flex w-screen justify-between">
        <ProfileMenu mobile={true} />
        <ApplicationLogo className="h-10 w-auto fill-current" />
        <div className="w-12" />
      </div>
      <main className={cn("w-full my-10", className)}>{children}</main>
      <NavigationMenu className="fixed bottom-0 left-0 h-16 w-screen border-t bg-background z-50">
        <NavigationMenuList className="w-screen px-8 py-4 flex items-center justify-between">
          <NavigationMenuItem className="flex flex-col items-center">
            <NavLink href={route("home")}>
              <House size={24} />
            </NavLink>
            <span className="text-xs">Home</span>
          </NavigationMenuItem>
          <NavigationMenuItem className="flex flex-col items-center">
            <NavLink href={route("search.index")}>
              <Search size={24} />
            </NavLink>
            <span className="text-xs">Search</span>
          </NavigationMenuItem>
          <NavigationMenuItem className="flex flex-col items-center">
            <NavLink href={route("home")} className="text-primary">
              <CirclePlus size={42} />
            </NavLink>
          </NavigationMenuItem>
          <NavigationMenuItem className="flex flex-col items-center">
            <NavLink href={route("chats.index")}>
              <Mail size={24} />
            </NavLink>
            <span className="text-xs">Chats</span>
          </NavigationMenuItem>
          <NavigationMenuItem className="flex flex-col items-center">
            <NavLink href={route("profile.edit")} className="flex flex-col">
              <User size={24} />
            </NavLink>
            <span className="text-xs">Profile</span>
          </NavigationMenuItem>
        </NavigationMenuList>
      </NavigationMenu>
    </div>
  );
}
