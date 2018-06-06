,/**
 * Created by mads- on 19-04-2017.
 */
function validate(formObj) {
     var result = 1;

     $.each($(formObj).find(":enabled"), function (index, value) {
              if ($(this).data("requried")) {
                   switch (this.type) {
                        case "text":
                        case "password":
                        case "textarea":
                        case "tel":
                             if (!$(this).val()) {
                                  result = 0;
                                  showError($(this), "Feltet må ikke være tomt!");
                                  $(this).bind("keydown", function () {
                                       removeError($(this));
                                       result = 1;
                                  });
                                  return false;
                             }
                             break;
                        case "email":
                             if (!isValidEmail($(this).val())) {
                                  showError($(this), "Email skal være gyldig!");
                                  result = 0;
                                  return false;
                             } else {
                                  removeError($(this));
                                  result = 1;
                             }
                             break;
                        case "checkbox":
                             if (!$(this).is(":checked")) {
                                  showError($(this), "<br>Du skal acceptere vores betingelser!<br>");
                                  result = 0;
                                  return false;
                             } else {
                                  removeError($(this));
                                  result = 1;
                             }
                             break;
                   }

                   switch ($(this).data("validate")) {
                        case "password":
                             if (!isValidLength($(this).val(), 8, 20)) {
                                  showError($(this), "Adgangskoden skal være mellem 8 og 20 tegn");
                                  result = 0;
                                  return false;
                             } else {
                                  removeError($(this));
                                  result = 1;
                             }
                             break;
                        case "passwordConfirm":
                             if ($("#password").val() != ($("#password_confirm")).val()) {
                                  showError($(this), "Password er ikke ens");
                                  result = 0;
                                  return false;
                             }
                             else {
                                  removeError($(this));
                                  result = 1;
                             }
                             break;
                        case "username":
                             if (!isValidLength($(this).val(), 3, 10) || !isValidAlpha($(this).val())) {
                                  showError($(this), "Username skal være mellem 3 og 10 tegn <br> Username må kun være bogstaver");
                                  result = 0;
                                  return false;
                             } else {
                                  removeError($(this));
                                  result = 1;
                             }
                             break;
                        case "fullname":
                             if (!isValidAlpha($(this).val())) {
                                  showError($(this), "Feltet må kun være bogstaver!");
                                  result = 0;
                                  return false;
                             } else {
                                  removeError($(this));
                                  result = 1;
                             }
                             break;
                        case "Telefon":
                             if (!isValidNumber($(this).val()) || (!isValidLength($(this).val(), 8, 8))) {
                                  showError($(this), "Feltet må kun være tal!! <br> Telefon nummeret skal være 8 tegn!");
                                  result = 0;
                                  return false;
                             } else {
                                  removeError($(this));
                                  result = 1;
                             }
                             break;
                        case "Zip":
                             if (!isValidNumber($(this).val()) || (!isValidLength($(this).val(), 4, 5))) {
                                  showError($(this), "Feltet må kun være tal!! <br> Postnummeret skal være mellem 4 og 5 tal!!");
                                  result = 0;
                                  return false;
                             } else {
                                  removeError($(this));
                                  result = 1;
                             }
                             break;

                   }
              }
         }
     )
     ;

     if (result) {
          return true;
     } else {
          return false;
     }
}

$(function () {
     $("[data-validate]").each(function () {
          var form = this;
          $(form).submit(function (e) {
               if (!validate(form)) {
                    e.preventDefault();
               }
          })
     })
});

function showError(objInput, strError) {
     var arrClasses = Array(
         "text-danger",
         "has-error",
         "has-success"
     );
     if (
         //if "!" true then add this HTML
         !$(objInput).next().hasClass(arrClasses[0])) {
          $(objInput).after("<h3 class='text-default' style='color: red'>" + strError + "</h3>");
          $(objInput).parent().addClass(arrClasses[1]);
     }
}

function removeError(objInput) {
     var arrClasses = Array(
         "text-danger",
         "has-error"
     );
     if (
         $(objInput).next().hasClass(arrClasses[0])) {
          $(objInput).next().remove();
          $(objInput).parent().removeClass(arrClasses[1]);
     }
}

/*
 * RegEx & Matching functions
 * Gift from Heinz
 */

//Tjekker om værdi er et nummer

/**
 * Value = .val()
 * */

function isValidNumber(value) {
     var pattern = /^[0-9]+$/;
     return pattern.test(value);
}

/**
 * Value = .val()
 * */

//Tjekker om værdi er alfabet
function isValidAlpha(value) {
     var pattern = /^[A-ZÆØÅa-zæøå ]+$/;
     return pattern.test(value);
}

/**
 * Value = .val()
 * */
//Tjekker om værdi har en gyldig email syntaks
function isValidEmail(value) {
     var pattern = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
     return pattern.test(value);
}

/**
 * Value = .val()
 * Min = Minimum(int 1-200)
 * Max = Maximum(int 1-200)
 * */
//Tjekker at værdi har en gyldig lændge
function isValidLength(value, min, max) {
     var pattern = RegExp('^[0-9A-Za-z@#$%]{' + min + ',' + max + '}$');
     return pattern.test(value);
}
