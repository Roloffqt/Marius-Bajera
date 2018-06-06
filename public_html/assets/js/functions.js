/**
 * Created by mads- on 17-05-2017.
 */
$(function () {
     
     var carouselHeight = $("#carousel").height();
     
     $(".carousel-inner > .item > .image").css("height", carouselHeight + "px");
     
});
$(document).ready(function () {
     $('.navbar-nav [data-toggle="tooltip"]').tooltip();
     $('.navbar-twitch-toggle').on('click', function (event) {
          event.preventDefault();
          $('.navbar-twitch').toggleClass('open');
     });
     
     $('.nav-style-toggle').on('click', function (event) {
          event.preventDefault();
          var $current = $('.nav-style-toggle.disabled');
          $(this).addClass('disabled');
          $current.removeClass('disabled');
          $('.navbar-twitch').removeClass('navbar-' + $current.data('type'));
          $('.navbar-twitch').addClass('navbar-' + $(this).data('type'));
     });
});
$(".addtocart").click(function () {
     var quantity = $("#quantity-" + $(this).data("productid"));
     if (quantity.val() > 0) {
          $.ajax({
               type: "POST",
               url: "/assets/scripts/addtocart.php",
               data: "iProductID=" + $(this).data("productid") + "&iQuantity=" + quantity.val(),
               success: function (result) {
                    console.log(result);
                    var obj = $.parseJSON(result);
                    $("#product" + obj.iProductID + " .isincart").text("Dette produkt ligger i kurven");
                    $("#productsInCart").text(obj.productsInCart);
                    //console.log(obj);
               }
          });
     }
     else {
          quantity[0].setCustomValidity("Antallet skal være større end 0");
          quantity[0].reportValidity();
     }
});

$(".remove").click(function () {
     $.ajax({
          type: "POST",
          url: "/assets/scripts/removeproduct.php",
          data: "iProductID=" + $(this).data("productid"),
          success: function (result) {
               var obj = $.parseJSON(result);
               $("#product" + obj.productId).remove();
               location.reload();
          }
     });
});

$(".update").click(function () {
     var quantity = $("#quantity-" + $(this).data("id"));
     if (quantity.val() > 0) {
          
          $.ajax({
               type: "POST",
               url: "/assets/scripts/updateQuantity.php",
               data: "cartlineid=" + $(this).data("id") + "&quantity=" + quantity.val(),
               
               success: function (result) {
                    console.log(result);
                    var obj = $.parseJSON(result);
                    if (obj.status) {
                         $("#cartline" + obj.cartlineid + " .isincart").text("Antal er rettet");
                         $("#productsInCart").text(obj.productsInCart);
                         var price = $("#cartline" + obj.cartlineid + " .price").text();
                         price = parseFloat(price);
                         pricesum = price * quantity.val();
                         $("#cartline" + obj.cartlineid + " .linetotal").text(pricesum + ",-");
                         
                         //Grandtotal
                         $('.total-sum').text(obj.cartTotal + ' ' + obj.priceFormat); // Updates the "value"
                         
                    }
               }
          });
     }
     else {
          quantity[0].setCustomValidity("Antallet skal være større end 0");
          quantity[0].reportValidity();
     }
});

$(".search").click(function () {
     console.log(11);
     $.ajax({
          type: "POST",
          url: "/assets/scripts/search.php",
          success: function (result) {
          }
     });
});

// ===== Scroll to Top ====
$(window).scroll(function () {
     if ($(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
          $('#return-to-top').fadeIn(200);    // Fade in the arrow
     } else {
          $('#return-to-top').fadeOut(200);   // Else fade out the arrow
     }
});
$('#return-to-top').click(function () {      // When arrow is clicked
     $('body,html').animate({
          scrollTop: 0                       // Scroll to top of body
     }, 500);
});
