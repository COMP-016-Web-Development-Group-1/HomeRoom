<div class="px-6 py-4">
    <table id="default-table">
        <thead>
            <tr>
                {{ $header }}
            </tr>
        </thead>
        <tbody>
            {{ $body ?? '' }}
        </tbody>
    </table>
</div>

@pushOnce('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (document.getElementById("default-table")) {
                const dataTable = new DataTable("#default-table", {
                    searchQuerySeparator: "+",
                    classes: {
                        input: "table-search",
                        selector: "table-selector",
                    }
                });
            }
        });
    </script>
@endPushOnce
