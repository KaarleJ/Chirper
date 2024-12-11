import Chirp from "@/Components/Chirp";
import Header from "@/Components/Header";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Chirp as ChirpType } from "@/types";
import { Head } from "@inertiajs/react";
import Comment from "@/Components/Comment";

export default function Show({ chirp }: { chirp: ChirpType }) {
  return (
    <AuthenticatedLayout>
      <Head title="Chirp" />
      <Header title="Chirp" />
      <Chirp chirp={chirp} />
      {chirp.comments.map((comment) => (
        <Comment key={comment.id} comment={comment} />
      ))}
    </AuthenticatedLayout>
  );
}
