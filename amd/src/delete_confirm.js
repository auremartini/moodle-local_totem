define(['jquery', 'core/modal_factory', 'core/modal_events'], function($, ModalFactory, ModalEvents) {
    return {
        init: function() {
            $('a.icon-delete').on('click', function(e) {
                var clickedLink = $(e.currentTarget);
                ModalFactory.create({
                    type: ModalFactory.types.SAVE_CANCEL,
                    title: 'Delete item',
                    body: '<p>Do you really want to delete?</p>'
                })
                .then(function(modal) {
                    modal.setSaveButtonText('Delete');
                    modal.getRoot().on(ModalEvents.save, function() {
                        window.location.href = clickedLink[0].attributes.src.value + "&action=delete";
                    });
                    modal.show();
                });
            });
        }
    };
});