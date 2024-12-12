import SearchBar from "@/Components/SearchBar";
import SideNav from "@/Components/SideNav";
import { useMediaQuery } from "@/hooks/useMediaQuery";
import { cn } from "@/lib/utils";
import { PropsWithChildren } from "react";
import MobileAuthenticatedLayout from "./MobileAuthenticatedLayout";

export default function Authenticated({
  children,
  className,
  hideSearch,
}: PropsWithChildren & { hideSearch?: boolean; className?: string }) {
  const isDesktop = useMediaQuery("(min-width: 768px)");
  if (!isDesktop) {
    return (
      <MobileAuthenticatedLayout className={cn("w-full my-10", className)}>
        {children}
      </MobileAuthenticatedLayout>
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
