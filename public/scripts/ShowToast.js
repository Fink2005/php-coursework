const showToast = (message, type = "success") => {
  const toastConfig = {
    text: message,
    duration: 3000,
    gravity: "top",
    position: "center",
    className: "rounded-xl",
    style: {},
  };

  if (type === "success") {
    toastConfig.className += " bg-blue-500";
  } else if (type === "error") {
    toastConfig.style.background = "red";
  } else if (type === "warning") {
    toastConfig.style.background = "orange";
  }
  Toastify(toastConfig).showToast();
};
const toastMessage = sessionStorage.getItem("toastMessage");
const parsetoastMessage = JSON.parse(toastMessage) || undefined;
if (parsetoastMessage) {
  showToast(parsetoastMessage.message, parsetoastMessage.type);
  sessionStorage.removeItem("toastMessage");
}
