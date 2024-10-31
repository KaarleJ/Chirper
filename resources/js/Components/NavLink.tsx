import { cn } from "@/lib/utils";
import { Link, InertiaLinkProps } from "@inertiajs/react";

export default function NavLink({
  href,
  children,
  className,
  ...props
}: InertiaLinkProps) {
  return (
    <Link
      href={href}
      className={cn(
        "flex items-center gap-4 text-foreground text-xl font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500",
        className
      )}
      {...props}
    >
      {children}
    </Link>
  );
}
