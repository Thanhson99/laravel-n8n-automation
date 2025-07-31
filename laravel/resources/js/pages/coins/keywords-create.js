/**
 * Handle keyword + optional tags input to send via hidden inputs.
 * - Displays tags as removable blocks
 * - Preserves data via <input type="hidden" name="tags[]">
 *
 * Requires: jQuery + Bootstrap 5
 */
$(function () {
    const $form = $('#keywordForm');
    const $tagInput = $('#tagInput');
    const $keywordList = $('#keywordList');

    /**
     * Create a tag block with a hidden input
     * @param {string} tag
     */
    function addTag(tag) {
        const tagId = 'tag-' + Date.now();

        const $block = $(`
            <div class="badge bg-primary text-white me-2 mb-2 p-2 rounded-pill d-inline-flex align-items-center" id="${tagId}">
                <span>#${tag}</span>
                <button type="button" class="btn-close btn-close-white btn-sm ms-2 remove-tag" data-tag-id="${tagId}" aria-label="Remove"></button>
            </div>
        `);

        const $hiddenInput = $(`<input type="hidden" name="tags[]" value="${tag}" data-tag-id="${tagId}">`);

        $keywordList.append($block);
        $form.append($hiddenInput);
    }

    /**
     * Handle Enter on tag input to add tag block
     */
    $tagInput.on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            const tag = $tagInput.val().trim();
            if (tag) {
                addTag(tag);
                $tagInput.val('');
            }
        }
    });

    /**
     * Remove tag block + hidden input
     */
    $(document).on('click', '.remove-tag', function () {
        const tagId = $(this).data('tag-id');
        if (confirm('Remove this tag?')) {
            $('#' + tagId).remove();
            $('input[data-tag-id="' + tagId + '"]').remove();
        }
    });
});
