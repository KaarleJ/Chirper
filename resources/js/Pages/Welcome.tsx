import ApplicationLogo from "@/Components/ApplicationLogo";
import NavLink from "@/Components/NavLink";
import { Button } from "@/Components/ui/button";
import { Head } from "@inertiajs/react";
import { FcGoogle as Google } from "react-icons/fc";
import { FaGithub as Github } from "react-icons/fa";

export default function Welcome() {
  return (
    <>
      <Head title="Welcome to Chirper" />
      <div className="w-full min-h-screen grid grid-cols-2 px-24 gap-2">
        <div className="w-full flex justify-center items-center">
          <ApplicationLogo className="w-[30rem] h-auto" />
        </div>
        <div className="w-full flex flex-col justify-start items-start my-40">
          <h1 className="text-6xl font-extrabold my-8">Connect now</h1>
          <h2 className="text-4xl font-extrabold my-8">Join today.</h2>

          <Button
            className="rounded-full text-lg w-[17rem] my-2"
            variant="secondary"
          >
            <a
              href={"/auth/redirect/github"}
              className="flex items-center gap-2"
            >
              <Github size={36} />
              Signup with Github
            </a>
          </Button>

          <Button
            className="rounded-full text-lg w-[17rem] my-2"
            variant="secondary"
          >
            <a
              href={"/auth/redirect/google"}
              className="flex items-center gap-2"
            >
              <Google size={36} />
              Signup with Google
            </a>
          </Button>
          <div className="flex m-2 w-full items-center gap-2">
            <hr className="text-accent w-[7rem]" />
            <p>or</p>
            <hr className="text-accent w-[7rem]" />
          </div>
          <Button asChild className="rounded-full text-lg w-[17rem] my-2">
            <NavLink href={route("register")}>Create an account</NavLink>
          </Button>

          <h3 className="text-2xl font-semibold mt-8 mb-4">Already have an account?</h3>

          <Button asChild className="rounded-full text-lg w-[17rem] my-2">
            <NavLink href={route("login")}>Login</NavLink>
          </Button>
        </div>
      </div>
    </>
  );
}
