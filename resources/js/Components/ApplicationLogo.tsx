import { ImgHTMLAttributes } from "react";

export default function ApplicationLogo(
  props: ImgHTMLAttributes<HTMLImageElement>
) {
  return <img src="Chirper.png" alt="Chirper" {...props} />;
}
