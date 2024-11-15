import { useForm, usePage } from "@inertiajs/react";
import { Input } from "./ui/input";
import { Search } from "lucide-react";
import { FormEvent } from "react";
import { Button } from "./ui/button";

export default function SearchBar() {
  const { url } = usePage();
  const { data, setData, get } = useForm({
    query: "",
    strategy: "people",
  });

  const submit = (e: FormEvent) => {
    e.preventDefault();
    get(route("search.index"));
  };

  const isSearch = url.startsWith("/search");

  return (
    <div className="min-h-screen px-16 py-8 border-l flex flex-col items-center justify-start">
      {!isSearch && (
        <form className="relative w-[20rem]" onSubmit={submit}>
          <Input
            className="rounded-full pl-4 w-full"
            placeholder="search"
            value={data.query}
            onChange={(e) => setData("query", e.target.value)}
          />
          <Button
            className="absolute top-0 right-2 rounded-full"
            size="icon"
            variant="ghost"
            type="submit"
          >
            <Search size={24} />
          </Button>
        </form>
      )}
      <div className="w-[20rem]">Lorem Ipsum</div>
    </div>
  );
}
