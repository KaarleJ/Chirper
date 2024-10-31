import { Input } from "./ui/input";
import { Search } from "lucide-react";

export default function SearchBar() {
  return (
    <div className="min-h-screen p-16 border-l flex flex-col items-center justify-start">
      <div className="relative w-[20rem]">
        <Input className="rounded-full pl-10 w-full" placeholder="search" />
        <Search size={24} className="absolute top-2 left-2" />
      </div>
    </div>
  );
}
