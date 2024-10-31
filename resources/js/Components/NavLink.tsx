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
        "flex items-center gap-4 text-foreground text-xl font-semibold hover:opacity-50 transition-all",
        className
      )}
      {...props}
    >
      {children}
    </Link>
  );
}
