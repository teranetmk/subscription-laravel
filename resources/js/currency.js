document.addEventListener('DOMContentLoaded', function() {
    let priceInput = document.getElementById('price-input');

    if(priceInput) {
        priceInput.addEventListener('keyup', formatCurrency);
        priceInput.addEventListener('blur', formatCurrency);
    }

    function formatNumber(n) {
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function formatCurrency(event) {

        let input_value = event.target.value;
        if (input_value === '') { return; }
        let input_length = input_value.length;
        let cursor_position = event.target.selectionStart;

        if (input_value.indexOf('.') >= 0) {
            let decimal_position = input_value.indexOf('.');
            let left_side = input_value.substring(0, decimal_position);
            let right_side = input_value.substring(decimal_position);
            left_side = formatNumber(left_side);
            right_side = formatNumber(right_side);
            if(event.type == 'blur') {
                right_side += '00';
            }
            right_side = right_side.substring(0, 2);
            input_value = '$' + left_side + '.' + right_side;
        } else {
            input_value = formatNumber(input_value);
            input_value = '$' + input_value;

            if (event.type == 'blur') {
                input_value += '.00';
            }
        }

        event.target.value = input_value;
        document.getElementById('cents').value = priceInput.value.replace(/[^0-9]/g, '');

        let updated_length = input_value.length;
        cursor_position = updated_length - input_length + cursor_position;
        event.target.setSelectionRange(cursor_position, cursor_position);

    }
});
