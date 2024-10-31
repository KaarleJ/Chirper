export default function Header({ title }: { title: string }) {

  return (
    <div className="w-full border-b p-8 text-xl font-semibold">
      <h1>{title}</h1>
    </div>
  );
}
