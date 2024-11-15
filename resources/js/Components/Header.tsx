import { cn } from "@/lib/utils";

export default function Header({
  title,
  className,
}: {
  title: string;
  className?: string;
}) {
  return (
    <div className={cn("w-full border-b p-8 text-xl font-semibold", className)}>
      <h1>{title}</h1>
    </div>
  );
}
