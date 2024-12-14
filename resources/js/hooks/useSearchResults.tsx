import { SearchResults } from "@/types";
import { usePage } from "@inertiajs/react";
import { useState } from "react";
import { FormEvent } from "react";

export default function useSearchResults() {
  const {
    results: initialResults,
    query: initialQuery,
    strategy: initialStrategy,
  } = usePage().props;
  const [results, setResults] = useState<SearchResults<"people" | "chirps">>(
    initialResults as SearchResults<"people" | "chirps">
  );
  const [query, setQuery] = useState<string>(initialQuery as string);
  const [strategy, setStrategy] = useState<"people" | "chirps">(
    initialStrategy as "people" | "chirps"
  );
  const [loading, setLoading] = useState(false);

  async function submitSearch(newStrategy?: "people" | "chirps") {
    setLoading(true);
    try {
      const results = await window.axios.get(route("search.get"), {
        params: { query, strategy: newStrategy || strategy },
      });
      setResults(results.data);
      setLoading(false);
    } catch (error) {
      console.error(error);
      setLoading(false);
    }
  }

  function changeStrategy(e: FormEvent) {
    e.preventDefault();
    const newStrategy = strategy === "people" ? "chirps" : "people";
    setStrategy(newStrategy);
    submitSearch(newStrategy);
  }

  return {
    loading,
    results,
    strategy,
    query,
    setQuery,
    setStrategy,
    submitSearch,
    changeStrategy,
  };
}
