import React from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { useForm, Head } from "@inertiajs/react";
import Chirp from "@/Components/Chirp";
import { Chirp as ChirpType } from "@/types";
import Header from "@/Components/Header";
import { Button } from "@/Components/ui/button";

export default function Index({ chirps }: { chirps: ChirpType[] }) {
  const { data, setData, post, processing, reset, errors } = useForm({
    message: "",
  });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route("chirps.store"), { onSuccess: () => reset() });
  };

  return (
    <AuthenticatedLayout>
      <Head title="Home" />
      <Header title="Home" />
      <div className="w-full">
        <form onSubmit={submit} className="p-8 border-b">
          <textarea
            value={data.message}
            placeholder="What's on your mind?"
            className="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            onChange={(e) => setData("message", e.target.value)}
          />
          <p className="text-destructive">{errors.message}</p>
          <Button className="mt-4" disabled={processing}>
            Chirp
          </Button>
        </form>
        {chirps.map((chirp) => (
          <Chirp key={chirp.id} chirp={chirp} />
        ))}
      </div>
    </AuthenticatedLayout>
  );
}
