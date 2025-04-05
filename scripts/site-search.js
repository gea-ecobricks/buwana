function openSearch() {
    document.body.style.overflow = 'hidden';
    document.body.style.maxHeight = '100vh';
    document.body.style.overflowY = "clip";

    var modal = document.getElementById('right-search-overlay');
    modal.style.width = "100%";

    modal.setAttribute('tabindex', '0');
    modal.focus();

    document.addEventListener('focus', function(event) {
        if (!modal.contains(event.target)) {
            event.stopPropagation();
            modal.focus();
        }
    }, true);

    // Hide the search results initially
    document.getElementById('search-results').style.display = 'none';
}

/* Close when someone clicks on the "x" symbol inside the overlay */
function closeSearch() {
    document.getElementById("right-search-overlay").style.width = "0%";

    // Allow scrolling on the body again
    document.body.style.overflow = '';
    document.body.style.maxHeight = '';
}

function clearResults() {
    document.getElementById('search_input').value = '';
    document.getElementById('search-results').innerHTML = '';
    var overlayContent = document.querySelector('.search-overlay-content');
    overlayContent.style.height = '';
    overlayContent.style.marginTop = '';
}

function handleKeyPress(event) {
    if (event.keyCode === 13) { // 13 is the key code for the enter key
        event.preventDefault(); // Prevent the default action to stop form submission
        ecobrickSearch(); // Call your search function without arguments
    }
}

function ecobrickSearch() {
    var query = document.getElementById("search_input").value.trim();

    // Display the search results section
    document.getElementById('search-results').style.display = 'block';

    // Check if DataTable is already initialized
    if ($.fn.DataTable.isDataTable("#ecobrick-search-return")) {
        // If already initialized, update the search parameter and reload the table
        var table = $("#ecobrick-search-return").DataTable();
        table.settings()[0].ajax.data = function(d) {
            d.searchValue = query; // Update the search query
        };
        table.ajax.reload();
    } else {
        // Initialize DataTables if not already initialized
        $("#ecobrick-search-return").DataTable({
            "responsive": true,
            "serverSide": true,
            "processing": true,
            "ajax": {
                "url": "../api/fetch_newest_briks.php",
                "type": "POST",
                "data": function(d) {
                    d.searchValue = query; // Send the search query to the server
                }
            },
            "pageLength": 10, // Show only 10 results at a time

            "language": {
                "emptyTable": "No ecobricks match your search.",
                "info": "Showing _START_ to _END_ of _TOTAL_ ecobricks",
                "infoEmpty": "No ecobricks available",
                "loadingRecords": "Loading ecobricks...",
                "processing": "Processing...",
                "search": "",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            },
            "columns": [
                { "data": "ecobrick_thumb_photo_url" }, // Brik column
                { "data": "weight_g" },
                { "data": "volume_ml" },
                { "data": "ecobricker_maker" }, // Maker column
                { "data": "date_logged_ts" },
                { "data": "location_brik" },
                { "data": "status" },
                { "data": "serial_no" } // Serial column
            ],
            "columnDefs": [
                { "orderable": false, "targets": [0, 6] }, // Make the image and status columns unsortable
                { "className": "all", "targets": [0, 3, 7] }, // Ensure Brik (thumbnail), Maker, and Serial always display
                { "className": "min-tablet", "targets": [1, 2, 4] }, // These fields can be hidden first on smaller screens
                { "className": "none", "targets": [5] } // Allow Location text to wrap as needed
            ],
            "initComplete": function() {
                var searchBox = $("div.dataTables_filter input");
                searchBox.attr("placeholder", "Search within results...");
            }
        });
    }
}





