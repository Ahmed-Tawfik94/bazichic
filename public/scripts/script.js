var base_url = window.location.origin;

function handleFormSubmit(formSelector, overlaySelector, authorization, swalTitle, swalText,progressBarSelector,callback=null) {
  $(formSelector).submit(function (e) {
    e.preventDefault();

    var form = $(formSelector);
    var data = new FormData(this);
    const overlay = $(overlaySelector);
    const formEl = $(formSelector);
    // Optional Progress Bar
    let progressBar = $(progressBarSelector);
    let bar = progressBar ? progressBar.find(".progress-bar") : null;
    let barText = progressBar ? progressBar.find(".progress-text") : null;

    // Show progress bar if it exists
    if (bar) {
      progressBar.show();
      bar.css("width", "0%");
      barText.text("0%");
    }

    /********######## THEN SWAL #######*******/
    swal({
      title: swalTitle || "Upload Cover",  // Default title
      text: swalText || "Are you sure that you want to upload this cover?", // Default text
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Confirm",
    }).then((result) => {
      if (result.value) {
        overlay.show();
        formEl.hide();
        $.ajax({
          url: form.attr("action"),
          type: "POST",
          data: data,
          beforeSend: function (request) {
            request.setRequestHeader("Authorization", authorization);
          },
          processData: false,
          contentType: false,
          dataType: "json",
          // Handle file upload progress
          xhr: function () {
            const xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
              if (evt.lengthComputable) {
                const percentComplete = Math.floor((evt.loaded / evt.total) * 100);
                if (bar) {
                  bar.css("width", percentComplete + "%");
                  barText.text(percentComplete + "%");
                }

                if (percentComplete >= 100) {
                  // Optionally hide the progress bar once done
                  if (progressBar) {
                    setTimeout(function () {
                      progressBar.hide();
                    }, 500); // Hide after a brief delay
                  }
                }
              }
            }, false);
            return xhr;
          },
          success: function (data) {
            overlay.hide();
            formEl.show();
            if (data.error) {
              toastr.error(data.message);
            } else {
              toastr.success(data.message);
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            overlay.hide();
            formEl.show();
            console.log(thrownError);
            console.log(xhr.responseJSON);
            if (xhr.responseJSON) {
              // Assuming the response has an error message in 'message' key
              toastr.error(xhr.responseJSON.message || xhr.responseText);
            } else {
              toastr.error(xhr.responseText);  // Fallback if JSON is not returned
            }
          },
          completed:function(){
            if(callback){
            callback()
          }
          }
        });
      }
    });
  });
}
function handleClick(formSelector,endpoint, authorization, swalTitle, swalText) {
  $(formSelector).click(function (e) {
    e.preventDefault();
    const id = $(this).attr("data-id");
    const row = $(this).closest("tr");

    /********######## THEN SWAL #######*******/
    swal({
      title: swalTitle || "Upload Cover",  // Default title
      text: swalText || "Are you sure that you want to upload this cover?", // Default text
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Confirm",
    }).then((result) => {
      if (result.value) {
        var formData = JSON.stringify({ id })
        $.ajax({
          url: `${base_url}/${endpoint}`,
          type: "POST",
          data: formData,
          beforeSend: function (request) {
            request.setRequestHeader("Authorization", authorization);
          },
          processData: false,
          contentType: 'Application/json',
          dataType: "json",
          success: function (data) {
            if (data.error) {
              toastr.error(data.message);
            } else {
              toastr.success(data.message);
              row.animate({ opacity: 0, height: 0 }, 1000, function () {
                $(this).remove();
              });
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError);
            console.log(xhr.responseJSON);
            if (xhr.responseJSON) {
              // Assuming the response has an error message in 'message' key
              toastr.error(xhr.responseJSON.message || xhr.responseText);
            } else {
              toastr.error(xhr.responseText);  // Fallback if JSON is not returned
            }
          },
        });
      }
    });
  });
}

$(document).ready(function () {

  handleClick(".deleteFAQ", "admin/apis/faqs/delete", authorization, "Delete FAQ", "Are you sure you want to delete this FAQ?")
  handleClick('.deleteDocument', "admin/documents/delete", authorization, "Delete Document", "Are you sure you want to delete this document?")
  handleClick(".deleteCategory", "admin/categories/delete", authorization, "Delete Category", "Are you sure you want to delete this category?")

  handleFormSubmit("#assignSubscriptionForm", '#formOverlay', authorization, "Assign Subscriptions", "Are you sure about this assignment?",null,()=>{
    $.magnificPopup.close()
  })
  handleFormSubmit("#updateProfileForm", '#register_overlay', authorization, "Update Profile", "Do you want to update profile?")
  handleFormSubmit("#siteConfigForm", '#siteConfigForm', authorization, "Save Settings", "Are you sure you want to save this settings?")
  // handleFormSubmit("#updateMembershipForm", null, authorization, "Update Subscription plan", "Do you want to update this subscription")
  handleFormSubmit("#createMembershipForm", null, authorization, "Create New Membership", "Do you want to create this subscription?")
  handleFormSubmit("#bookMembershipForm", null, authorization, "Book Membership", "Are you sure with your action?")
  handleFormSubmit("#addCategoryForm", '', authorization, 'Create New Category', 'Are you sure you want to save this category?')
  handleFormSubmit("#updateCategoryForm", '', authorization, 'Update Category', 'Are you sure you want update this category?')
  handleFormSubmit("#addDocumentForm", '#create_doc_overlay', authorization, 'Create new Document', "Are you sure that you want to save this Document?")
  handleFormSubmit("#docFileUploadForm", "#doc_file_overlay", authorization, 'Upload File', "Are you sure you want to upload this file?")
  handleFormSubmit('#docMediaUploadForm', "#doc_cover_overlay", authorization, "Upload Cover", "Are you sure that you want to upload this cover?")
  handleFormSubmit('#docAudioUploadForm', "#doc_audio_overlay", authorization, "Upload Audio", "Are you sure that you want to upload this audio?")
  handleFormSubmit("#updateDocumentForm", '#update_doc_overlay', authorization, "Document Update", "Are you sure that you want to update this Document?")
  handleFormSubmit('.deleteDocument', null, authorization, "Delete Document", "Are you sure you want to delete this document?")
  handleFormSubmit("#profileForm", "#profileFormOverlay", authorization, "Save Profile", "Do you want to Save the profile Changes?")
  handleFormSubmit("#profileCredForm", null, authorization, "Update Password", "Are you sure you want to update password?")

});