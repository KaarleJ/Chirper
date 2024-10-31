import SearchBar from "@/Components/SearchBar";
import SideNav from "@/Components/SideNav";
import { PropsWithChildren } from "react";

export default function Authenticated({ children }: PropsWithChildren) {
  return (
    <div className="min-h-screen flex justify-between item-start px-8 md:px-18 2xl:px-52">
      <SideNav />
      <main className="min-h-screen w-full">{children}</main>
      <SearchBar />
    </div>
  );
}
