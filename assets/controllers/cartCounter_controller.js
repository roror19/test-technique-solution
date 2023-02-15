import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        $.ajax({
            type: "GET",
            url: $(this.element).data('link'),
            success: function (data) {

                $('#counter').text(data)
            }
        });
    }
}