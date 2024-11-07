import { FormEvent, useState } from "react";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import { Link, useForm, usePage } from "@inertiajs/react";
import { Chirp as ChirpType } from "@/types";
import { Button } from "./ui/button";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "./ui/dropdown-menu";
import { Ellipsis, MessageCircleMore } from "lucide-react";

dayjs.extend(relativeTime);

export default function Chirp({ chirp }: { chirp: ChirpType }) {
  const { auth } = usePage().props;

  const [editing, setEditing] = useState(false);

  const { data, setData, patch, clearErrors, reset, errors } = useForm({
    message: chirp.message,
  });

  const submit = (e: FormEvent) => {
    e.preventDefault();
    patch(route("chirps.update", chirp.id), {
      onSuccess: () => setEditing(false),
    });
  };

  return (
    <div className="p-6 flex space-x-2">
      <MessageCircleMore className="text-gray-600 mt-2" />
      <div className="flex-1">
        <div className="flex justify-between items-center">
          <div>
            <span className="text-gray-800">{chirp.user.name}</span>
            <small className="ml-2 text-sm text-gray-600">
              {dayjs(chirp.created_at).fromNow()}
            </small>
            {chirp.created_at !== chirp.updated_at && (
              <small className="text-sm text-gray-600"> &middot; edited</small>
            )}
          </div>
          <DropdownMenu>
            <DropdownMenuTrigger>
              <Button className="rounded-full" variant="ghost" size="icon">
                <Ellipsis className="text-gray-600" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent>
              <DropdownMenuItem className="block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out">
                <button
                  onClick={() => setEditing(true)}
                  className="w-full text-left"
                >
                  Edit
                </button>
              </DropdownMenuItem>
              <DropdownMenuItem className="block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out">
                <Link
                  as="button"
                  href={route("chirps.destroy", chirp.id)}
                  method="delete"
                  className="w-full text-left"
                >
                  Delete
                </Link>
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
        {editing ? (
          <form onSubmit={submit}>
            <textarea
              value={data.message}
              onChange={(e) => setData("message", e.target.value)}
              className="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            ></textarea>
            <p className="text-destructive">{errors.message}</p>
            <div className="space-x-2">
              <Button>Save</Button>
              <button
                className="mt-4"
                onClick={() => {
                  setEditing(false);
                  reset();
                  clearErrors();
                }}
              >
                Cancel
              </button>
            </div>
          </form>
        ) : (
          <p className="mt-4 text-lg text-gray-900">{chirp.message}</p>
        )}
      </div>
    </div>
  );
}
