import { Chirp as ChirpType } from "@/types";
import { Link } from "@inertiajs/react";
import Chirp from "./Chirp";

export default function ChirpCard({ chirp }: { chirp: ChirpType }) {
  return (
    <Link href={`/chirps/${chirp.id}`}>
      <Chirp chirp={chirp} />
    </Link>
  );
}
