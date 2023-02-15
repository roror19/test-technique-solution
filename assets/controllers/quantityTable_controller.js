import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        $(this.element).on('click', function () {
            let type = $(this).data('type');

            let quantity = $(this)
                .closest('.quantity-table')
                .find('input')
                .val();

            if (type === 'minus') {
                quantity = quantity - 1;
            } else {
                quantity = parseInt(quantity) + 1;
            }

            let productId = $(this).data('id');
            let url       = $(this).data('link');

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    'productId': productId,
                    'quantity': quantity
                },
                success: function (data) {

                    window.location.reload()
                }
            });
        })
    }
}
