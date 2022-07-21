/*
 * Welcome to your app's main JavaScript file!
 *ya
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';


const mobileScreen = window.matchMedia("(max-width: 990px )");
$(document).ready(function () {

    setInterval(function () {
        $.ajax({
            type: 'POST',
            url: Routing.generate('check_count_product_in_cart'),
            success: function (data) {
                console.log(data);
                $(".left-int").text(data);
            }
        });
    }, 10000);


    $(".dashboard-nav-dropdown-toggle").click(function (e) {
        e.preventDefault();
        $(this).closest(".dashboard-nav-dropdown")
            .toggleClass("show")
            .find(".dashboard-nav-dropdown")
            .removeClass("show");
        $(this).parent()
            .siblings()
            .removeClass("show");
    });
    $(".menu-toggle").click(function (e) {
        e.preventDefault();
        if (mobileScreen.matches) {
            $(".dashboard-nav").toggleClass("mobile-show");
        } else {
            $(".dashboard").toggleClass("dashboard-compact");
        }
    });

    $(".add-to-cart").on('click', function () {
        let productId = $(this).data('id')

        if (!$(this).hasClass('active')) {
            $(this).addClass('active');
            $.ajax({
                url: Routing.generate('product_add_cart', {'id': productId}),
                type: "POST",
                data: {
                    method: 1
                },
            });
        } else {
            $(this).removeClass('active');
            $.ajax({
                url: Routing.generate('product_add_cart', {'id': productId}),
                type: "POST",
                data: {
                    method: 0
                },
            });

        }

        return false;
    });

    $('.add-another-collection-widget').click(function (e) {
        e.preventDefault();

        let list = $($(this).attr('data-list'));

        let counter = list.data('widget-counter') | list.children().length;

        let newWidget = list.attr('data-prototype');

        newWidget = newWidget.replace(/__name__/g, counter);

        newWidget = newWidget + '<a href="" class="remove-another-collection-widget" data-list="#delete_fields-list">Удалить свойство товара</a>';

        counter++;

        list.data('widget-counter', counter);


        let newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });

    $(document).on('click', '.remove-another-collection-widget', function (e) {
        e.preventDefault();
        $(this).closest('ul').remove();
    });


    $(document).on('change', '.file-upload', function (e) {

        let formData = new FormData();
        formData.append('file', this.files[0]);

        $.ajax({
            type: 'POST',
            url: Routing.generate('upload_img'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            enctype: "multipart/form-data",
            success: function (data) {
                // console.log(data);
                addImageToCollection(data);
            },
            error: function (data) {
                // console.log(data);
            }
        });

    });

    $(document).on('click', '.collection-add', function (e) {
        e.preventDefault();
        let imageCount;
        let $collectionContainer = $('#' + $(this).data('collection'));
        if (!imageCount) {
            imageCount = $collectionContainer.children().length;
        }
        let prototype = $collectionContainer.attr('data-prototype');
        let item = prototype.replace(/__name__/g, imageCount);
        $collectionContainer.append(item);
        imageCount++;
    });

    $(document).on('click', '.icon-deleted', function (e) {
        e.preventDefault();
         $(this).closest('.image-item').remove();
    });


});

function addImageToCollection(filename) {
    let imageContainer = $('#image-container');

    let newWidget = imageContainer.attr('data-prototype');
    let counter = imageContainer.data('widget-counter') | imageContainer.children().length;

    newWidget = newWidget.replace(/__name__/g, counter);
    newWidget = newWidget + `
             <div class="img-relative">
               <a href="#" class="icon-deleted">X</a>
               <img src="/uploads/${filename}" class="img-add">              
             </div>          
    `;
    counter++;
    imageContainer.data('widget-counter', counter);

    let newElem = $(imageContainer.attr('data-widget-tags')).html(newWidget);
    newElem.find('.image-filename').val(filename);
    newElem.appendTo(imageContainer);
}
