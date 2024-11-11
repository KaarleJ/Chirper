import { Button } from "@/Components/ui/button";
import { useForm } from "@inertiajs/react";
import { FormEventHandler, useState } from "react";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogTitle,
  DialogTrigger,
} from "@/Components/ui/dialog";

export default function DeleteUserForm({
  className = "",
}: {
  className?: string;
}) {
  const [open, setOpen] = useState(false);

  const { post, processing } = useForm({
    password: "",
  });

  const deleteUser: FormEventHandler = (e) => {
    e.preventDefault();

    post(route("profile.requestDelete"), {
      preserveScroll: true,
      onSuccess: () => () => setOpen(false),
    });
  };

  return (
    <section className={`space-y-6 ${className}`}>
      <Dialog open={open} onOpenChange={() => setOpen(!open)}>
        <DialogTrigger asChild>
          <Button variant="destructive" onClick={() => setOpen(true)}>
            Delete Account
          </Button>
        </DialogTrigger>
        <DialogContent>
          <DialogTitle>
            Are you sure you want to delete your account?
          </DialogTitle>
          <DialogDescription>
            A confirmation email will be sent to you.
          </DialogDescription>
          <form onSubmit={deleteUser}>
            <div className="flex justify-start">
              <Button variant="secondary" onClick={() => setOpen(false)}>
                Cancel
              </Button>

              <Button
                variant="destructive"
                className="ms-3"
                disabled={processing}
              >
                Delete Account
              </Button>
            </div>
          </form>
        </DialogContent>
      </Dialog>
    </section>
  );
}
