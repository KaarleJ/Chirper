import SideNav from "@/Components/SideNav";
import { PropsWithChildren } from "react";

export default function Authenticated({ children }: PropsWithChildren) {
  return (
    <div className="min-h-screen flex justify-between item-start px-60">
      <SideNav />
      <main className="min-h-screen w-full">{children}</main>
      <div className="min-h-screen">Leftmenu Lorem ipsum</div>
    </div>
  );
}
