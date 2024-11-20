import Header from "@/Components/Header";
import { Button } from "@/Components/ui/button";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Chat } from "@/types";
import { Head } from "@inertiajs/react";
import { MailPlus as NewMessage } from "lucide-react";

export default function Chats(/*{ chats }: { chats: Chat }*/) {
  const chats: Chat[] = [
    {
      userOne: 1,
      userTwo: 2,
      messages: [],
    },
  ];
  return (
    <AuthenticatedLayout>
      <Head title="Chats" />
      <div className="flex items-center justify-between border-b">
        <Header title="Chats" className="border-b-0" />
        <Button className="mr-12 rounded-full p-2" size="icon">
          <NewMessage />
        </Button>
      </div>

      <div className="py-12">Chats</div>
    </AuthenticatedLayout>
  );
}
