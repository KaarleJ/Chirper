import Chirp from "@/Components/Chirp";
import Header from "@/Components/Header";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Chirp as ChirpType } from "@/types";
import { Head } from "@inertiajs/react";
import Comment from "@/Components/Comment";

const user = {
  id: 1,
  name: "John Doe",
  email: "john@gmail.com",
  email_verified_at: "2021-09-01T00:00:00.000000Z",
  profile_picture: "https://i.pravatar.cc/150?u=johndoe",
  username: "johndoe",
  is_following: false,
  is_social: false,
};

const mockComments = [
  {
    id: 1,
    user,
    message: "This is a comment!",
    created_at: "1 hour ago",
    updated_at: "1 hour ago",
  },
  {
    id: 2,
    user,
    message: "This is another comment!",
    created_at: "2 hours ago",
    updated_at: "1 hour ago",
  },
  {
    id: 3,
    user,
    message: "This is a reply!",
    created_at: "3 hours ago",
    updated_at: "1 hour ago",
  },
  {
    id: 4,
    user,
    message: "This is a reply to a reply!",
    created_at: "4 hours ago",
    updated_at: "1 hour ago",
  },
];

export default function Show({ chirp }: { chirp: ChirpType }) {
  return (
    <AuthenticatedLayout>
      <Head title="Chirp" />
      <Header title="Chirp" />
      <Chirp chirp={chirp} />
      {mockComments.map((comment) => (
        <Comment key={comment.id} comment={comment} />
      ))}
    </AuthenticatedLayout>
  );
}
