import ApplicationLogo from "./ApplicationLogo";
import ProfileMenu from "./ProfileMenu";

export default function MobileTopBar() {
  return (
    <div className="absolute top-0 left-0 h-16 px-8 py-4 flex w-screen justify-between">
      <ProfileMenu mobile={true} />
      <ApplicationLogo className="h-10 w-auto fill-current" />
      <div className="w-12"/>
    </div>
  );
}
