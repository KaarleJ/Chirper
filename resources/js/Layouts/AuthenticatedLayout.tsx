import MobileNavBar from "@/Components/MobileNavBar";
import MobileTopBar from "@/Components/MobileTopBar";
import SearchBar from "@/Components/SearchBar";
import SideNav from "@/Components/SideNav";
import { useMediaQuery } from "@/hooks/useMediaQuery";
import { cn } from "@/lib/utils";
import { PropsWithChildren } from "react";

export default function Authenticated({
  children,
  className,
  hideSearch,
}: PropsWithChildren & { hideSearch?: boolean; className?: string }) {
  const isDesktop = useMediaQuery("(min-width: 768px)");
  if (!isDesktop) {
    return (
      <div className="flex flex-col item-start">
        <MobileTopBar />
        <main className={cn("w-full my-10", className)}>{children}</main>
        <MobileNavBar />
      </div>
    );
  }
  return (
    <div className="min-h-screen flex justify-between item-start px-8 md:px-18 2xl:px-52">
      <SideNav />
      <main className={cn("min-h-screen w-full", className)}>{children}</main>
      {!hideSearch && <SearchBar />}
    </div>
  );
}
