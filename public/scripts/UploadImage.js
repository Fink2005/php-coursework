const handleUploadImage = async (img) => {
  try {
    const response = await $.ajax({
      url: "/course-work/Ajax/upload",
      type: "POST",
      data: img,
      processData: false,
      contentType: false,
    });

    if (response.success) {
      showToast("Uploaded image successful", "success");
      return response.image_url.url;
    } else {
      showToast("Error uploading image", "error");
      return false;
    }
  } catch (error) {
    showToast("Error uploading image", "error");
    console.error("AJAX error:", error);
    return false;
  }
};
