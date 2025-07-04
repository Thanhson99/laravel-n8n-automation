import $ from 'jquery';
import 'datatables.net-bs5';
import toastr from 'toastr';

$(function () {
    const table = $('.datatable').DataTable({
        order: [],
        columnDefs: [
            { orderable: false, targets: 4 },
            { targets: '_all', orderSequence: ['asc', 'desc', ''] }
        ]
    });

    /**
     * Update sorting icons in table header based on current DataTable order.
     *
     * - Reset all icons to default (neutral state).
     * - Highlight active sort column with up/down icon and color.
     * - Handle 3rd click (reset state) by showing default icon again.
     */
    const updateSortIcons = () => {
        const order = table.order(); // Current order: e.g., [[0, 'asc']]

        // Reset all icons to default
        $('thead th').each(function (index) {
            const icon = $(this).find('i.fas');

            if (!icon.length) return;

            icon
                .removeClass('fa-sort fa-sort-up fa-sort-down text-primary')
                .addClass('fa-sort text-muted');
        });

        // Apply icon only if a column is currently sorted
        if (order.length && order[0].length === 2) {
            const [colIndex, dir] = order[0];

            if (dir === 'asc' || dir === 'desc') {
                const th = $('thead th').eq(colIndex);
                const icon = th.find('i.fas');

                if (icon.length) {
                    icon.removeClass('fa-sort text-muted');
                    icon.addClass(dir === 'asc' ? 'fa-sort-up text-primary' : 'fa-sort-down text-primary');
                }
            }
        }
    };

    updateSortIcons();
    table.on('order.dt', updateSortIcons);

    // Handle favorite toggle
    $('.favorite-toggle').on('click', function (e) {
        e.preventDefault();

        const symbol = $(this).data('symbol');
        const icon = $(this).find('i');
        const toggleFavoriteUrl = $('meta[name="toggle-favorite-url"]').attr('content');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: toggleFavoriteUrl,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: { symbol },
            success: function (response) {
                if (response.success) {
                    icon.toggleClass('fas text-danger far text-muted');
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || 'Something went wrong.');
                }
            },
            error: function () {
                toastr.error('Server error. Please try again.');
            }
        });
    });
});
