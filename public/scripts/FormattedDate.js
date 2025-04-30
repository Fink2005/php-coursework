const formattedDate = (date, type = "default") => {
  const convertedDate = new Date(date);
  if (type === "formatDistanceToNow") {
    return dateFns.formatDistanceToNow(date, { addSuffix: true });
  }
  return dateFns.format(convertedDate, "MMMM d, yyyy");
};
