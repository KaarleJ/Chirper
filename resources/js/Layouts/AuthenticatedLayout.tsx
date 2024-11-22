import Header from "@/Components/Header";
import SearchBar from "@/Components/SearchBar";
import SideNav from "@/Components/SideNav";
import { cn } from "@/lib/utils";
import { PropsWithChildren } from "react";

export default function Authenticated({
  children,
  className,
  hideSearch,
}: PropsWithChildren & { hideSearch?: boolean; className?: string }) {
  return (
    <div className="min-h-screen flex justify-between item-start px-8 md:px-18 2xl:px-52">
      <SideNav />
      <main className={cn("min-h-screen w-full", className)}>{children}</main>
      {!hideSearch && <SearchBar />}
    </div>
  );
}
