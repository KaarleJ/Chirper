import Header from "@/Components/Header";
import { Button } from "@/Components/ui/button";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { Input } from "@/Components/ui/input";
import { cn } from "@/lib/utils";
import useSearchResults from "@/hooks/useSearchResults";
import SearchResults from "@/Components/SearchResults";
import { LoaderCircle } from "lucide-react";

export default function Search() {
  const { results, strategy, setQuery, submitSearch, changeStrategy, loading } =
    useSearchResults();

  return (
    <AuthenticatedLayout>
      <Head title="Search" />
      <Header title="Search" className="border-b-0 pb-4" />
      <div className="border-b">
        <form
          onSubmit={(e) => {
            e.preventDefault();
            submitSearch();
          }}
        >
          <div className="px-8 py-4 w-[25rem] flex gap-2">
            <Input
              className="rounded-full pl-4 w-full"
              placeholder="search"
              onChange={(e) => setQuery(e.target.value)}
            />
          </div>
        </form>
        <div className="flex px-4 gap-4">
          <Button
            className={cn(
              "text-lg relative",
              strategy === "people" && underline
            )}
            variant="ghost"
            onClick={changeStrategy}
          >
            People
          </Button>
          <Button
            className={cn(
              "text-lg relative",
              strategy === "chirps" && underline
            )}
            variant="ghost"
            onClick={changeStrategy}
          >
            Chirps
          </Button>
        </div>
      </div>

      {loading ? (
        <div className="w-full h-2/3 flex flex-col items-center justify-center">
          <LoaderCircle className="w-12 h-12 animate-spin text-primary" />
        </div>
      ) : (
        <SearchResults results={results} strategy={strategy} />
      )}
    </AuthenticatedLayout>
  );
}

const underline =
  "after:content-[''] after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-14 after:h-1 after:bg-primary after:rounded-full";
