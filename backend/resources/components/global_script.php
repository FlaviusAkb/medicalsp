<?php $currentPath = $_ENV["CURRENT_PATH"]; ?>
<script src="<?php echo $_ENV["CURRENT_PATH"]; ?>/js/qof.js"></script>
<script>
    let currentPath = `<?php echo $_ENV["CURRENT_PATH"]; ?>`;
    let filesAllowed = <?php echo $_ENV["FILES_ALLOWED"]; ?>;
    let maxFileSize = <?php echo $_ENV["MAX_SIZE"]; ?>;
    let pattern_pass = <?php echo $_ENV["PASSREGEX"]; ?>;
    let phoneRegex = <?php echo $_ENV["PHONEREGEX"]; ?>;
    let cp = '<?php echo $_ENV["CURRENT_PATH"]; ?>';

    function detectDevice() {
        const ua = navigator.userAgent;
        if (/iPad|Android(?!.*Mobile)|Tablet|Nexus 7|Nexus 10|KF[A-Z]+/i.test(ua)) {
            return 'tablet';
        } else if (/Mobile|Mobi|Android|iPhone|iPod|IEMobile|Opera Mini/i.test(ua)) {
            return 'mobile';
        } else {
            return 'desktop';
        }
    }
    const deviceType = detectDevice();

    function fixContaining(parent, image) {
        function resize() {
            if (image.offsetWidth <= parent.offsetWidth) {
                image.style.setProperty("--imgWidth", "100%");
                image.style.setProperty("--imgHeight", "auto");
            }
            if (image.offsetHeight <= parent.offsetHeight) {
                image.style.setProperty("--imgWidth", "auto");
                image.style.setProperty("--imgHeight", "102vh");
            } else {
                image.style.setProperty("--imgWidth", "100%");
                image.style.setProperty("--imgHeight", "auto");
            }
        }
        if (image.tagName === 'VIDEO') {
            image.addEventListener("loadedmetadata", () => {
                resize();
            });
        } else {
            image.addEventListener("load", () => {
                resize();
            });
        }
        window.addEventListener("resize", () => {
            resize();
        });
    }
</script>
<script src="<?php echo $currentPath; ?>/js/jquery.min.js"></script>
<?php autoFiles('js/auto'); ?>


<?php
/*
#####################################           /admin/widget
*/
$requestUri = $_SERVER['REQUEST_URI'];
if (strpos($requestUri, '/admin/widget') !== false) {
?>
    <script>
        const controllerPath = currentPath + '/api/widget';

        function loadWidgets() {
            $("#widget-list").html("<p>Loading...</p>");
            $.ajax({
                url: controllerPath,
                type: 'POST',
                data: {
                    action: 'getAll'
                },
                success: function(response) {
                    let widgets;
                    try {
                        widgets = JSON.parse(response);
                        // If the response is not an array, set widgets to an empty array
                        if (!Array.isArray(widgets)) {
                            widgets = [];
                        }
                    } catch (e) {
                        console.error("Failed to parse response:", e);
                        widgets = [];
                    }
                    if (widgets.length === 0) {
                        $("#widget-list").html("<p>No widgets found.</p>");
                    } else {
                        renderWidgets(widgets);
                    }
                }
            });
        }

        function renderWidgets(widgets) {
            let html = `<h2 class="font-raleway font-semibold text-black text-[17px] mb-[20px] text-left w-full text-center">Widgets (${widgets.length})</h2>`;

            html += `<input type="text" id="search" class="mb-4 p-2 w-full border border-gray-300 rounded" placeholder="Search widgets...">
             <div class="overflow-x-auto">
                <table id="widgets-table" class="table-auto w-full border-collapse border">
                    <thead>
                        <tr>
                            <th class="border p-2">Position</th>
                            <th class="border p-2">Title</th>
                            <th class="border p-2">Image</th>
                            <th class="border p-2">Notes</th>
                            <th class="border p-2">Status</th>
                            <th class="border p-2">Added</th>
                            <th class="border p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>`;

            // Loop through all widgets and display them in a single table
            widgets.forEach(widget => {
                const position = widget.position;
                const title = widget.title || 'No Title';
                const website_url = widget.website_url || 'Unknown website_url';
                const notes = widget.notes || 'N/A';
                const publishDate = widget.publish_date || 'N/A';
                const status = widget.status === 1 ? 'Published' : 'Draft';
                const image_url = widget.image_url ? `${ widget.image_url}` : `${currentPath + "/upload/siteMedia/placeholdery.png"}`;
                html += `
                <tr draggable="true" ondragstart="dragit(event)" ondragover="dragover(event)" data-id="${widget.id}">
                    <td class="border p-2">${position + 1}</td>
                    <td class="border p-2"><a href="${website_url}" class="hover:text-msp-primary duration-300 ease-in">${title}</a></td>
                    <td class="border p-2 w-[250px]"><div class="flex w-full"><img src="${image_url}" alt=""></div></td>
                    <td class="border p-2 w-[250px] h-[100px] overflow-y-scroll">${notes}</td>
                    <td class="border p-2">${status}</td>
                    <td class="border p-2">${publishDate}</td>
                    <td class="border p-2">
                        <div class="flex flex-col h-full w-full items-center gap-2">
                            <button class="edit-btn bg-msp-accent text-white px-4 py-2 rounded w-[5rem] cursor-pointer" data-id="${widget.id}">Edit</button>
                            <button class="delete-btn bg-msp-primary text-white px-4 py-2 rounded w-[5rem] cursor-pointer" data-id="${widget.id}">Delete</button>
                        </div>
                    </td>
                </tr>`;
            });

            html += `</tbody></table></div>`;
            $('#widget-list').html(html);

            // Add event listener to search input
            document.getElementById("search").addEventListener("input", function() {
                filterTable(this.value);
            });

            // Initialize drag and drop for reordering
            const tableBody = document.querySelector("#widgets-table tbody");
            tableBody.addEventListener("dragover", function(event) {
                event.preventDefault();
                const dragged = document.querySelector(".dragging");
                const target = event.target.closest("tr");

                if (target && target !== dragged) {
                    const bounding = target.getBoundingClientRect();
                    const offset = event.clientY - bounding.top;

                    if (offset < bounding.height / 2) {
                        tableBody.insertBefore(dragged, target);
                    } else {
                        tableBody.insertBefore(dragged, target.nextSibling);
                    }
                }
            });


            tableBody.addEventListener("dragstart", function(event) {
                event.target.closest("tr").classList.add("dragging");
            });

            tableBody.addEventListener("dragend", function(event) {
                event.target.closest("tr").classList.remove("dragging");
                saveNewOrder();
            });
        }

        // Filter table function for search
        function filterTable(query) {
            const rows = document.querySelectorAll("#widgets-table tbody tr");
            rows.forEach(row => {
                const textContent = row.textContent.toLowerCase();
                const searchText = query.toLowerCase();
                if (textContent.includes(searchText)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        function makeCrud(jInfo) {
            const leftFields = [{
                    id: "title",
                    label: "Title",
                    type: "text"
                },
                {
                    id: "website_url",
                    label: "Website URL",
                    type: "text"
                },
                {
                    id: "image_file",
                    label: "Image",
                    type: "file"
                },
                {
                    id: "publish_date",
                    label: "Publish Date",
                    type: "date"
                }
            ];

            const selectFields = [{
                id: "status",
                label: "Status",
                options: [{
                    value: 1,
                    text: "Published"
                }, {
                    value: 0,
                    text: "Draft"
                }]
            }];

            let inputsHTML = leftFields.map(field => `
                    <div class="relative">
                        <input id="edit-${field.id}" type="${field.type}" name="${field.id}" 
                            class="peer leading-8 mb-4 block border p-2 px-4 rounded w-full placeholder-transparent focus:outline-none focus:ring-2 focus:ring-black"
                            placeholder="" value="${jInfo[field.id] || ''}"/>
                        <label for="${field.id}" class="absolute left-0 ml-2 -top-2.5 bg-white px-2 text-gray-600
                            duration-200 transform scale-100 origin-[0] 
                            peer-placeholder-shown:top-2.5 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 
                            peer-focus:-top-2.5 peer-focus:text-black">${field.label}</label>
                    </div>
                `).join('');

            let selectHTML = selectFields.map(field => `
                    <div class="relative">
                        <select id="edit-${field.id}" name="${field.id}" class="peer border p-2 leading-8 mb-4 px-4 rounded w-full appearance-none bg-white focus:outline-none focus:ring-2 focus:ring-black">
                            ${field.options.map(option => 
                                `<option value="${typeof option === 'object' ? option.value : option}" 
                                    ${jInfo[field.id] == (typeof option === 'object' ? option.value : option) ? 'selected' : ''}>
                                    ${typeof option === 'object' ? option.text : option}
                                </option>`).join('')}
                        </select>
                        <label for="${field.id}" class="absolute left-0 ml-2 -top-2.5 bg-white px-2 text-gray-600
                            duration-200 transform scale-100 origin-[0] 
                            peer-placeholder-shown:top-2.5 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 
                            peer-focus:-top-2.5 peer-focus:text-black">${field.label}</label>
                    </div>
                `).join('');

            const crudBody = document.createElement("div");
            crudBody.className = `inset-0 flex justify-center items-center`;
            crudBody.id = `editModal`;
            crudBody.innerHTML = `
                    <form id="edit-widget-form" enctype="multipart/form-data" class="flex flex-col w-full gap-4">
                        <div class="flex gap-4 flex-col md:flex-row">
                            <div class="flex flex-col w-full md:w-1/2">
                                <input type="hidden" id="edit-widget-id" name="id" value="${jInfo.id ? jInfo.id : ''}">
                                ${inputsHTML}
                                ${selectHTML}
                            </div>
                           <div id="image-preview-wrapper" class="relative mb-6">
                            <img id="image-preview" class="h-[150px]" src="${jInfo.image_url ? jInfo.image_url : currentPath + '/upload/siteMedia/placeholder.png'}" 
                                alt="Preview"
                                onerror="this.src='${currentPath}/upload/siteMedia/placeholdery.png'}">
                            <button type="button" id="remove-image-btn" class="absolute top-0 right-0 bg-red-600 text-white px-2 py-1 text-xs rounded cursor-pointer">Remove</button>
                            </div>
                            <div class="relative">
                                <textarea rows="6" id="edit-notes" name="notes" 
                                    class="peer block border p-4 rounded w-full placeholder-transparent focus:outline-none focus:ring-2 focus:ring-black" 
                                    placeholder="">${jInfo.notes || ''}</textarea>
                                <label for="notes" class="absolute left-0 ml-2 -top-2.5 bg-white px-2 text-gray-600
                                    duration-200 transform scale-100 origin-[0] 
                                    peer-placeholder-shown:top-2.5 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 
                                    peer-focus:-top-2.5 peer-focus:text-black">Notes</label>
                        </div>
                            </div>
                        </div>
                       
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded cursor-pointer">Save</button>
                            <button type="button" id="cancel-btn" class="bg-gray-400 text-white px-4 py-2 rounded ml-2 cursor-pointer">Cancel</button>
                        </div>
                    </form>
                `;

            document.body.appendChild(crudBody);
            const removeBtn = crudBody.querySelector("#remove-image-btn");
            const imagePreview = crudBody.querySelector("#image-preview");

            const fileInput = crudBody.querySelector("#edit-image_file");
            fileInput.addEventListener("change", (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        imagePreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            removeBtn?.addEventListener("click", () => {
                imagePreview.src = currentPath + "/upload/siteMedia/placeholder.png";
                const hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "remove_image";
                hiddenInput.value = "1";
                crudBody.querySelector("#edit-widget-form").appendChild(hiddenInput);
            });

            const newPop = new popUps({
                title: document.createTextNode(jInfo.id ? "Edit Widget" : "Add New Widget"),
                content: crudBody
            });
            newPop.htmlContent.style.cssText += `background:white; border:1px solid rgba(255,255,255,0.2); padding:2rem;`;
            newPop.htmlClose.style.cssText += `--_color:black;`;
            newPop.htmlUpBar.classList.add("flex", "w-full", "items-center", "font-bold", "text-2xl", "py-4", "justify-center", "text-black");

            const cancelButton = crudBody.querySelector("#cancel-btn");
            cancelButton.addEventListener("click", () => {
                if (newPop && typeof newPop.fInfo.cta.clsBtn === "function") {
                    newPop.fInfo.cta.clsBtn();
                } else {
                    console.warn("Close button function is not available.");
                }
            });
            newPop.show();
        }

        $(document).ready(function() {

            loadWidgets();


            $(document).on("submit", "#edit-widget-form", function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append("action", "save");

                $.ajax({
                    url: controllerPath,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.includes("success")) {
                            alert("Widget saved successfully!");
                            loadWidgets();
                        } else {
                            alert("Error saving widget.");
                        }
                    },
                    error: function() {
                        alert("Error saving widget.");
                    },
                });
            });

            $('#addWidgetBtn').click(function() {
                makeCrud({});
            });

            $(document).on("click", ".edit-btn", function() {
                const widgetId = $(this).data("id");

                $.ajax({
                    url: controllerPath,
                    type: "POST",
                    data: {
                        action: "getById",
                        id: widgetId
                    },
                    success: function(response) {
                        const widget = JSON.parse(response);
                        makeCrud(widget);
                    },
                    error: function() {
                        alert("Error fetching widget details.");
                    },
                });
            });

            $(document).on("click", ".delete-btn", function() {
                const widgetId = $(this).data("id");

                if (confirm("Are you sure you want to delete this widget?")) {
                    $.ajax({
                        url: controllerPath,
                        type: "POST",
                        data: {
                            action: "delete",
                            id: widgetId
                        },
                        success: function() {
                            alert("Widget deleted successfully!");
                            loadWidgets();
                        },
                        error: function() {
                            alert("Error deleting widget.");
                        },
                    });
                }
            });

        });

        let shadow;

        function dragit(event) {
            shadow = event.target;
        }

        function dragover(e) {
            let children = Array.from(e.target.parentNode.parentNode.children);
            if (children.indexOf(e.target.parentNode) > children.indexOf(shadow))
                e.target.parentNode.after(shadow);
            else
                e.target.parentNode.before(shadow);
        }

        function saveNewOrder() {
            const orderedWidgets = [];
            document.querySelectorAll("#widgets-table tbody tr").forEach((row, index) => {
                const widgetId = row.dataset.id;
                orderedWidgets.push({
                    id: widgetId,
                    position: index
                });
            });

            $.ajax({
                url: controllerPath,
                type: 'POST',
                data: {
                    action: 'reorder',
                    order: JSON.stringify(orderedWidgets)
                },
                success: function(response) {
                    console.log(response);
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        alert("Widgets reordered successfully!");
                        loadWidgets();
                    } else {
                        alert("Error reordering widgets.");
                    }
                },
                error: function() {
                    alert("Error reordering widgets.");
                }
            });
        }
    </script>
<?php
}
?>