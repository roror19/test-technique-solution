import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {

        $(this.element).on('click', function () {

            let productId = $(this).data('id');
            let url       = $(this).data('link');
            let quantity  = $(this).closest('.actions-buttons').find('input').val()

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    'productId': productId,
                    'quantity': quantity
                },
                success: function (data) {

                    $('#success-add').removeClass('d-none')
                    $('#counter').text(data)
                }
            });
        })
    }
}