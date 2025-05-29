var base_url = window.location.origin;

function getSubsPrice(obj) {
  var month = obj.value;
  //alert(month);

  if (month == "perYear") {
    document.getElementById("perMonthPremiumSubscription").display = "none";
    document.getElementById("perYearPremiumSubscription").display = "block";
  } else {
    document.getElementById("perMonthPremiumSubscription").display = "block";
    document.getElementById("perYearPremiumSubscription").display = "none";
  }
  //document.getElementById('subPrice').innerHTML = '$'+price+' USD';
  //document.getElementById('paypalValid').value = month;
}

$(document).ready(function () {
  //alert("Welcome to Bazichic");

  // Get query parameters
  const urlParams = new URLSearchParams(window.location.search);
  const message = urlParams.get("message");
  const status = urlParams.get("status");

  // Show SweetAlert if a message is present
  if (message) {
    swal({
      title: "",
      html: "<b>" + message + "</b>",
      type: status === "success" ? "success" : "error",
      showCancelButton: false,
      confirmButtonColor: "#3085d6",
      focusConfirm: false,
    }).then(() => {
      // Remove query parameters after SweetAlert is closed
      const urlWithoutParams =
        window.location.origin + window.location.pathname;
      window.history.replaceState({}, document.title, urlWithoutParams);
    });
  }

  $("#freeTrialsForm").submit(function (e) {
    e.preventDefault();
    //alert($(this).serialize());
    document.getElementById("trial_overlay").style.display = "block";
    document.getElementById("freeTrialsForm").style.display = "none";
    $.ajax({
      url: $(this).attr("action"),
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (data) {
        document.getElementById("trial_overlay").style.display = "none";
        document.getElementById("freeTrialsForm").style.display = "block";
        if (data.error) {
          swal({
            title: "",
            html: "<b>" + data.message + "</b>",
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            focusConfirm: false,
          });

          if (false) {
            //document.getElementById('approveLayout').style.display = 'block';
          }
        } else {
          //document.getElementById('resultHolder').innerHTML = '<hr><h3>'+data.message+'</h3>';
          console.log(object);
          window.location.replace(base_url + "/dashboard");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        //alert(xhr.status);
        //alert(thrownError);
      },
    });
  });

  $("#purchasePlanForm").submit(function (e) {
    e.preventDefault();
    //alert($(this).serialize());
    document.getElementById("membership_overlay").style.display = "block";
    document.getElementById("purchasePlanForm").style.display = "none";
    $.ajax({
      url: $(this).attr("action"),
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (data) {
        document.getElementById("membership_overlay").style.display = "none";
        document.getElementById("purchasePlanForm").style.display = "block";
        if (data.error) {
          swal({
            title: "",
            html: "<b>" + data.message + "</b>",
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            focusConfirm: false,
          });
        } else {
          //document.getElementById('resultHolder').innerHTML = '<hr><h3>'+data.message+'</h3>';
          window.location.replace(
            base_url + "/subscription/confirm-subscription/" + data.qcode
          );
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        //alert(xhr.status);
        //alert(thrownError);
      },
    });
  });

  $(".filterDocumentForm").submit(function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    //alert(data);
    //var categoryID = $('#filter_category_id').find('input[name="documentType"]').val();
    var categoryID = $("#filter_document_type option:selected").val();
    //alert("selected "+categoryID);
    /*
    switch(categoryID){
        case 1:
            window.location.replace(base_url+'/e-book-store/e-book');
            break;

         case 2:
            window.location.replace(base_url+'/e-book-store/audio-book');
            break;

         case 3:
            window.location.replace(base_url+'/e-book-store/magazine');
            break;
    }
    alert("selected "+categoryID);
    */

    $.ajax({
      url: $(this).attr("action"),
      type: "POST",
      data: data,
      dataType: "json",
      success: function (data) {
        if (data.error) {
          swal({
            title: "",
            html: "<b>" + data.message + "</b>",
            type: "error",
            showCancelButton: false,
            allowOutsideClick: false,
            confirmButtonColor: "#3085d6",
            focusConfirm: false,
          });
        } else {
          swal({
            title: "Message Sent Successfully.",
            html: "<b>" + data.message + "</b>",
            type: "success",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            focusConfirm: false,
          });
          setTimeout(function () {
            window.location.replace(base_url);
          }, 2200);
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        //alert(thrownError);
      },
    });
  });

  // $("#siteContactForm").submit(function (e) {
  //   e.preventDefault();
  //   $.ajax({
  //     url: $(this).attr("action"),
  //     type: "POST",
  //     data: $(this).serialize(),
  //     dataType: "json",
  //     success: function (data) {
  //       if (data.error) {
  //         swal({
  //           title: "",
  //           html: "<b>" + data.message + "</b>",
  //           type: "error",
  //           showCancelButton: false,
  //           allowOutsideClick: false,
  //           confirmButtonColor: "#3085d6",
  //           focusConfirm: false,
  //         });
  //       } else {
  //         swal({
  //           title: "Message Sent Successfully.",
  //           html: "<b>" + data.message + "</b>",
  //           type: "success",
  //           showCancelButton: false,
  //           confirmButtonColor: "#3085d6",
  //           focusConfirm: false,
  //         });
  //         setTimeout(function () {
  //           window.location.replace(base_url);
  //         }, 2200);
  //       }
  //     },
  //     error: function (xhr, ajaxOptions, thrownError) {
  //       alert(xhr.status);
  //       //alert(thrownError);
  //     },
  //   });
  // });
  $(document).ready(function () {
    $("#loginForm").submit(function (e) {
      e.preventDefault();  // Prevent default form submission

      const form = $(this);
      const overlay = $("#login_overlay");

      overlay.show();   // Show overlay while processing
      form.hide();      // Hide form during the request

      $.ajax({
        url: form.attr("action"),  // Ensure the correct form action is used
        type: "POST",
        data: form.serialize(),
        dataType: "json",
        success: function (data) {
          toastr.success("Logged in Successfully");

          // Redirect user
          let redirectUrl = data.redirection ? `${base_url}/${data.redirection}` : `${base_url}/dashboard`;
          setTimeout(() => {
            window.location.replace(redirectUrl);
          }, 1000); // Optional: Delay redirection slightly
        },
        error: function (xhr, ajaxOptions, thrownError) {
          overlay.hide();
          form.show();

          let errorMessage = "An error occurred. Please try again.";

          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
          } else if (xhr.status === 0) {
            errorMessage = "Network error. Please check your internet connection.";
          } else if (xhr.status === 500) {
            errorMessage = "Internal server error. Please try again later.";
          } else if (xhr.status === 404) {
            errorMessage = "Requested resource not found.";
          } else {
            errorMessage = xhr.responseText || errorMessage;
          }

          toastr.error(errorMessage);
        },
        complete: function () {
          overlay.hide();
          form.show();
        }
      });
    });
  });


  $("#loginPageForm").submit(function (e) {
    e.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, "slow");
    document.getElementById("login_page_overlay").style.display = "block";
    document.getElementById("loginPageForm").style.display = "none";
    $.ajax({
      url: $(this).attr("action"),
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (data) {
        document.getElementById("login_page_overlay").style.display = "none";
        document.getElementById("loginPageForm").style.display = "block";
        if (data.error) {
          swal({
            title: "",
            html: "<b>" + data.message + "</b>",
            type: "error",
            showCancelButton: false,
            allowOutsideClick: false,
            confirmButtonColor: "#3085d6",
            focusConfirm: false,
          });
        } else {
          // setTimeout(function () {
          //   document
          //     .getElementById("login_page_msg")
          //     .html("<h5>" + data.message + "</h5>");
          // }, 1500);

          if (data.redirection) {
            window.location.replace(base_url + data.redirection);
          } else {
            window.location.replace(base_url + "/dashboard");
          }
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.responseJSON.error) {
          swal({
            title: "",
            html: "<b>" + xhr.responseJSON.message + "</b>",
            type: "error",
            showCancelButton: false,
            allowOutsideClick: false,
            confirmButtonColor: "#3085d6",
            focusConfirm: false,
          });
        }
        document.getElementById("login_page_overlay").style.display = "none";
        document.getElementById("loginPageForm").style.display = "block";
        //alert(thrownError);
      },
    });
  });

  $("#registerForm").submit(function (e) {
    e.preventDefault();

    if ($("#check_agree").is(":checked")) {
      $("html, body").animate({ scrollTop: 0 }, "slow");
      document.getElementById("registerForm").style.display = "none";
      document.getElementById("register_overlay").style.display = "block";
      $.ajax({
        url: $(this).attr("action"),
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function (data) {
          if (data.error) {
          } else {
            swal({
              title: "",
              html: "<b>" + data.message + "</b>",
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#3085d6",
              focusConfirm: false,
            }).then((data) => {
              window.location.replace(base_url + "/dashboard");
            });
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(xhr);
          document.getElementById("register_overlay").style.display = "none";
          document.getElementById("registerForm").style.display = "block";
          swal({
            title: "",
            html: "<b>" + xhr.responseJSON.message + "</b>",
            type: "error",
            showCancelButton: false,
            allowOutsideClick: false,
            confirmButtonColor: "#3085d6",
            focusConfirm: false,
          });
          //alert(thrownError);
        },
      });
    } else {
      swal({
        title: "",
        html: "<b>You must agree that the information provided by you is correct.</b>",
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        focusConfirm: false,
      });
    }
  });

    const recoverPassForm = $('#recoverPassForm');
    recoverPassForm.submit(function (e) {
      debugger
    e.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, "slow");
    const recovery_overlay = $('#recovery_overlay');
    recovery_overlay.show();
    recoverPassForm.hide();
    $.ajax({
      url: $(this).attr("action"),
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (data) {
        recovery_overlay.hide();
        recoverPassForm.show();
          swal({
            title: "",
            html: "<b>" + data.message + "</b>",
            type: "success",
            showCancelButton: false,
            allowOutsideClick: false,
            confirmButtonColor: "#3085d6",
            focusConfirm: false,
          });
          setTimeout(function () {
            //document.getElementById('login_msg').html('<h5>'+data.message+'</h5>');
          }, 1500);

          setTimeout(function () {
            window.location.replace(base_url + "/login");
          }, 4000);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(xhr.status);
        recovery_overlay.hide();
        recoverPassForm.show();
        swal({
          title: "",
          html: "<b>" +thrownError + "</b>",
          type: "error",
          showCancelButton: false,
          allowOutsideClick: false,
          confirmButtonColor: "#3085d6",
          focusConfirm: false,
        });
        //alert(thrownError);
      },
    });
  });

  /**********######  REVIEWS  ###### *********/
  $("#reviewDocumentForm").submit(function (e) {
    e.preventDefault();
    //$("html, body").animate({ scrollTop: 0 }, "slow");
    const overlay = $("#rating_overlay");
    const form = $("#reviewDocumentForm");
    var stars = $("#rating").val();
    var plan_msg =
      "Are you sure you want to submit your review for this project.";
    if (stars < 0) {
      alert("Please rate this project to proceed.");
      return;
    }
    //alert($(this).serialize());

    swal({
      title: "Submit Review",
      html: "<b>" + plan_msg + "</b>",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      allowOutsideClick: false,
      confirmButtonText: "Confirm",
    }).then((result) => {
      if (result.value) {
        overlay.show();
        form.hide();
        // document.getElementById("rating_overlay").style.display = "block";
        // document.getElementById("reviewDocumentForm").style.display = "none";
        $.ajax({
          url: $(this).attr("action"),
          type: "POST",
          data: $(this).serialize(),
          dataType: "json",
          success: function (data) {
            if (data.error) {
              overlay.hide();
              form.show();
              // document.getElementById("rating_overlay").style.display = "none";
              // document.getElementById("reviewDocumentForm").style.display =
              //   "block";
              swal({
                title: "",
                html: "<b>" + data.message + "</b>",
                type: "error",
                allowOutsideClick: false,
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                focusConfirm: false,
              });
            } else {
              setTimeout(function () {
                location.reload();
              }, 2500);
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            //alert(thrownError);
            overlay.hide();
            form.show();
            // document.getElementById("form_overlay").style.display = "none";
            // document.getElementById("projectReviewArea").style.display =
            //   "block";
          },
        });
        /********** End of  Operation *********/
      }
    });
  });
  /********************************/

  /**********######  LIBRARY SAVE  ###### *********/
  $(".saveDocForm").submit(function (e) {
    e.preventDefault();
    var actionTitle = "Add to library?";
    var actionBtn = "Save Now";
    var actionMsg = "This document will be saved to your library.";
    var submit = $(this).closest("form").find(":submit");
    swal({
      title: actionTitle,
      html: "<b>" + actionMsg + "</b>",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: actionBtn,
    }).then((result) => {
      if (result.value) {
        submit.html('<i class="fa fa-refresh fa-spin"></i>Wait...');
        $.ajax({
          url: $(this).attr("action"),
          type: "POST",
          data: $(this).serialize(),
          dataType: "json",
          success: function (data) {
            if (data.error) {
              submit.html('<i class="fa fa-plus"></i> ADD TO LIBRARY');
              swal({
                title: "Failed To Save",
                html: "<b>" + data.message + "</b>",
                type: "error",
                showCancelButton: false,
                allowOutsideClick: false,
                confirmButtonColor: "#3085d6",
                focusConfirm: false,
              });
            } else {
              swal({
                title: data.title,
                html: "<b>" + data.message + "</b>",
                type: "success",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                focusConfirm: false,
              }).then((result) => {
                if (result.value) {
                  $(this).html(data.next_action);
                  window.location.reload();
                }
              });
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            //alert(thrownError);
          },
        });
        /********** End of  Operation *********/
      }
    });
  });
  /********************************/

  /**********######  LIKES  ###### *********/
  $(".favouriteDocument").click(function (e) {
    e.preventDefault();

    const btn = $(this);
    const icon = btn.find('i.bi-heart-fill');
    const docID = btn.attr("data-id");
    const inputTitle = parseInt(btn.attr("data-title"));
    const loading = $('<div id="loader"><i class="fa fa-refresh fa-spin"></i> Wait...</div>');

    // Dynamic messages based on favorite status
    const actionData = inputTitle === 1
        ? {
          title: "Confirm",
          message: "Are you sure that you want to remove your favourite mark from this document?",
          button: "Remove Now"
        }
        : {
          title: "Liked this document?",
          message: "Favourite this document to help others find their best interest. All your favourites will be available in bookmarks.",
          button: "Favourite Now"
        };

    swal({
      title: actionData.title,
      html: `<b>${actionData.message}</b>`,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: actionData.button,
      allowOutsideClick: false
    }).then((result) => {
      if (result.value) {
        icon.hide();
        btn.append(loading);

        $.ajax({
          url: base_url + "/documents/endorse",
          type: "POST",
          data: { doc_id: docID },
          dataType: "json",
          success: function (data) {
            swal({
              title: data.title,
              html: `<b>${data.message}</b>`,
              type: "success",
              confirmButtonColor: "#3085d6"
            }).then(() => {
              $('#loader').remove();
              icon.toggleClass('text-danger').show();
              btn.attr('data-title', inputTitle ? 0 : 1);
            });
          },
          error: function (xhr, ajaxOptions, thrownError) {
            swal({
              title: "Failed To Process",
              html: `<b>${thrownError}</b>`,
              type: "error",
              confirmButtonColor: "#3085d6"
            });
            btn.find('#loader').remove();
          }
        });
      }
    });
  });

  /********************************/
});
