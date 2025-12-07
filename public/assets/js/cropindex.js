   
        $(document).ready(function() {
            // Initialize DataTable with scrollable configuration
            var table = $('#cropPlansTable').DataTable({
                // Disable pagination - show all rows
                paging: false,
                
                // Enable scrolling
                scrollY: '600px',
                scrollCollapse: true,
                scroller: true,
                
                // Keep ordering and searching
                ordering: true,
                order: [[0, 'desc']], // Default sort by year descending
                
                // Search functionality
                searching: true,
                searchDelay: 500,
                
                // No info display (showing X of Y entries)
                info: false,
                
                // No length changing options
                lengthChange: false,
                
                // Responsive
                responsive: true,
                
                // Language settings
                language: {
                    emptyTable: "No crop plans available",
                    zeroRecords: "No matching plans found",
                    search: "Search:",
                    searchPlaceholder: "Search crop plans..."
                },
                
                // Column definitions for better UX
                columnDefs: [
                    {
                        targets: [0, 1, 2], // Year, Crop Type, Season columns
                        orderable: true,
                        searchable: true
                    },
                    {
                        targets: [3, 4, 5, 6, 7, 8, 9], // Data columns
                        orderable: true,
                        searchable: true
                    },
                    {
                        targets: [10], // Actions column
                        orderable: false,
                        searchable: false
                    }
                ],
                
                // Initialization complete callback
                initComplete: function() {
                    // Add custom search input
                    this.api().search('').draw();
                    
                    // Update the search box to use DataTable's search
                    $('#searchInput').on('keyup', function() {
                        table.search(this.value).draw();
                    });
                }
            });

            // Hover effects
            $('#cropPlansTable tbody').on('mouseenter', 'tr', function() {
                $(this).addClass('bg-light');
            }).on('mouseleave', 'tr', function() {
                $(this).removeClass('bg-light');
            });

            // Loading state for action buttons
            $('.action-group .btn').on('click', function(e) {
                if (!$(this).attr('href')) return;
                
                const $btn = $(this);
                const originalHtml = $btn.html();
                
                $btn.html('<i class="fas fa-spinner fa-spin"></i>');
                $btn.prop('disabled', true);
                
                setTimeout(() => {
                    $btn.html(originalHtml);
                    $btn.prop('disabled', false);
                }, 2000);
            });

            // Initialize tooltips
            $('[title]').tooltip({
                trigger: 'hover',
                placement: 'top'
            });

            // Export functions
            window.exportToExcel = function() {
                table.button('.buttons-excel').trigger();
            };

            window.exportToPDF = function() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('l', 'mm', 'a4');
                
                // Get table data from DataTable
                const tableData = [];
                table.rows().every(function() {
                    const row = this.data();
                    tableData.push(row);
                });
                
                doc.autoTable({
                    head: [['Year', 'Crop Type', 'Season', 'Crop', 'Area (ha)', 'Productivity', 'Expected Yield (qt)', 'Prev Year', 'Change', 'Trend']],
                    body: tableData,
                    theme: 'grid',
                    headStyles: { fillColor: [52, 58, 64] },
                    margin: { top: 20 },
                    didDrawPage: function(data) {
                        doc.text('Crop Plans Report', 14, 10);
                        doc.text('Generated on: {{ date("Y-m-d H:i:s") }}', 14, 16);
                    }
                });
                
                doc.save('crop-plans-{{ date("Y-m-d") }}.pdf');
            };

            window.printTable = function() {
                const printWindow = window.open('', '_blank');
                const tableHtml = $('#cropPlansTable').clone();
                
                // Remove DataTable classes for clean print
                tableHtml.removeClass('dataTable');
                tableHtml.find('.sorting, .sorting_asc, .sorting_desc').removeClass('sorting sorting_asc sorting_desc');
                
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Crop Plans Report</title>
                            <style>
                                body { font-family: Arial, sans-serif; margin: 20px; }
                                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                                th { background-color: #343a40; color: white; }
                                .text-right { text-align: right; }
                                .text-center { text-align: center; }
                                .badge { padding: 2px 6px; border-radius: 3px; font-size: 12px; }
                                .year-group, .type-group, .season-group { background-color: #f8f9fa; }
                            </style>
                        </head>
                        <body>
                            <h2>Crop Plans Report</h2>
                            <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
                            ${tableHtml[0].outerHTML}
                        </body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.print();
            };

            // Scroll to top function for the DataTable
            window.scrollToTop = function() {
                $('.dataTables_scrollBody').animate({
                    scrollTop: 0
                }, 500);
            };
            
            // Add button to scroll to bottom
            window.scrollToBottom = function() {
                const scrollBody = $('.dataTables_scrollBody');
                scrollBody.animate({
                    scrollTop: scrollBody[0].scrollHeight
                }, 500);
            };
            
            // Keyboard shortcuts
            $(document).keydown(function(e) {
                // Ctrl/Cmd + F to focus search
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    $('#searchInput').focus();
                }
                
                // Escape to clear search
                if (e.key === 'Escape') {
                    $('#searchInput').val('');
                    table.search('').draw();
                    $('#searchInput').focus();
                }
            });
        });
 