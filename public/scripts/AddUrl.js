$(document).ready(function () {
  function extractUrlContents(input) {
    const matches = input.match(/url\(([^)]*)\)/g) || [];
    return matches.map((match) => match.replace(/^url\((.*)\)$/, "$1"));
  }

  // Step 1: Add URL button sets input to url()
  $("#url").click(function () {
    $("#comment-input").val("url()");
    // Place cursor inside parentheses
    const input = document.getElementById("comment-input");
    input.focus();
    input.setSelectionRange(4, 4);
  });

  // Step 3: Preview button displays image
  $("#preview-tab").click(function () {
    let url = $("#comment-input").val().trim();

    const handleUrl = extractUrlContents(url).length
      ? extractUrlContents(url)[0]
      : "";
    console.log(extractUrlContents(url));

    // Extract URL from url() if present
    if (handleUrl.startsWith("url(") || handleUrl.endsWith(")")) {
      handleUrl = handleUrl.slice(4, -1).trim();
    }

    if (handleUrl) {
      // Set the background image of the preview div
      $("#previewImg").attr("src", handleUrl);
      $("#previewImg").removeClass("hidden");
    }
  });
});
