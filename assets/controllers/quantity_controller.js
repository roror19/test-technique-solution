import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        $(this.element).on('click', function () {
            let type = $(this).data('type');

            let quantity = $(this)
                .closest('.button-groups')
                .find('input')
                .val();

            if (type === 'minus') {
                quantity = quantity - 1;
            } else {
                quantity = parseInt(quantity) + 1;
            }

            if (quantity < 1) {
                quantity = 1;
            }

            $(this)
                .closest('.button-groups')
                .find('input')
                .val(quantity)
        })
    }
}
