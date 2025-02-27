jQuery(document).ready(function($) {
    
    $(document).on('click', '.syonet-add-value', function() {
        var field = $(this).data('field');
        var container = $(this).siblings('.syonet-values-container');
        var index = container.children('.syonet-value-row').length;

        var newRow = $('<div class="syonet-value-row form-group" style="grid-template-columns: 1.4fr 1fr 0.5fr!important; margin-bottom: 0px!important; gap: 10px!important;">' +
            '<input type="text" name="' + field + '[' + index + '][value]" placeholder="Valor do CRM" />' +
            '<input type="text" name="' + field + '[' + index + '][name]" placeholder="Aparece no formulário" />' +
            '<button type="button" class="button syonet-remove-value syonet-button syonet-button-danger">Remover</button>' +
            '</div>');

        container.append(newRow);
    });

    $(document).on('click', '.syonet-remove-value', function() {
        var confirmar = confirm("Tem certeza que deseja remover este item?");
        if (confirmar) {
            $(this).closest('.syonet-value-row').remove();
        }
    });
    
});
