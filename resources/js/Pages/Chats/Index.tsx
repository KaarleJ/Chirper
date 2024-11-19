import Header from "@/Components/Header";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";

export default function Chats() {
  return (
    <AuthenticatedLayout>
      <Head title="Chats" />
      <Header title="Chats" />

      <div className="py-12">
        Chats
      </div>
    </AuthenticatedLayout>
  );
}
