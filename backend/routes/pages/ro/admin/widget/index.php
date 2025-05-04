<?php
include "../" . $_ENV["BACKEND"] . "/classes/seo.php";
// TwoFactorAuth::require2FA();
// $currentPath = $_ENV["CURRENT_PATH"];
$currentPath = 'http://192.168.0.171';
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["LANG"]; ?>">

<head>
    <?php
    $seo->setOgDescription(
        localDic([
            "ro" => 'Exemplu de a scrie metadata',
            "eng" => 'Custom example of setting meta data',
        ])
    );
    require "../" . $_ENV["BACKEND"] . "/resources/components/head.php"; ?>
</head>

<body>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/nav.php"; ?>
    <div class="container flex flex-col px-6 mt-[50px] mb-[100px] mx-auto items-center justify-start md:gap-4 lg:px-4 xlg:max-w-full">
        <div class="flex w-full justify-end items-center">
            <form method="POST" action="<?php echo $currentPath ?>/api/2fa">
                <input type="hidden" name="case" value="logout">
                <button type="submit" class="bg-msp-primary text-white px-4 py-2 rounded mb-4 hover:bg-red-600 transition delay-150 duration-300 ease-in-out hover:-translate-y-1">Logout</button>
            </form>
        </div>
        <div id="widget-tabs" class="flex w-full"></div>
        <button id="addWidgetBtn" class="bg-green-500 text-white px-4 py-2 rounded mb-4">Add New Widget</button>
        <div id="widget-list"></div>
    </div>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/global_script.php"; ?>
    <script>
        const controllerPath = currentPath + '/api/widget';
        $(document).ready(function() {
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
            loadWidgets();

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
                        id: "position",
                        label: "Position",
                        options: [0, 1, 2, 3]
                    },
                    {
                        id: "status",
                        label: "Status",
                        options: [{
                            value: 1,
                            text: "Published"
                        }, {
                            value: 0,
                            text: "Draft"
                        }]
                    }
                ];

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
                                onerror="this.src='${currentPath}/upload/siteMedia/placeholder.png'}">
                            <button type="button" id="remove-image-btn" class="absolute top-0 right-0 bg-red-600 text-white px-2 py-1 text-xs rounded">Remove</button>
                            </div>
                            <div class="relative">
                                <textarea required rows="6" id="edit-notes" name="notes" 
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
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                            <button type="button" id="cancel-btn" class="bg-gray-400 text-white px-4 py-2 rounded ml-2">Cancel</button>
                        </div>
                    </form>
                `;

                document.body.appendChild(crudBody);
                const removeBtn = crudBody.querySelector("#remove-image-btn");
                const imagePreview = crudBody.querySelector("#image-preview");

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

            function renderWidgets(widgets) {
                let html = `<h2 class="font-raleway font-semibold text-black text-[17px] mb-[20px] text-left w-full text-center">Widgets</h2>`;

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
                    const position = widget.position || 'Not set';
                    const title = widget.title || 'No Title';
                    const website_url = widget.website_url || 'Unknown website_url';
                    const notes = widget.notes || 'N/A';
                    const publishDate = widget.publish_date || 'N/A';
                    const status = widget.status === "1" ? 'Published' : 'Draft';
                    const image_url = widget.image_url ? `${currentPath + "/" + widget.image_url}` : `${currentPath + "/upload/siteMedia/placeholder.png"}`;
                    html += `
                <tr>
                    <td class="border p-2">${position}</td>
                    <td class="border p-2"><a href="${website_url}" class="hover:text-msp-primary duration-300 ease-in">${title}</a></td>
                    <td class="border p-2 w-[250px]"><div class="flex w-full"><img src="${image_url}" alt=""></div></td>
                    <td class="border p-2 w-[250px] h-[100px] overflow-y-scroll">${notes}</td>
                    <td class="border p-2">${status}</td>
                    <td class="border p-2">${publishDate}</td>
                    <td class="border p-2">
                        <div class="flex flex-col h-full w-full items-center gap-2">
                            <button class="edit-btn bg-msp-accent text-white px-4 py-2 rounded w-[5rem]" data-id="${widget.id}">Edit</button>
                            <button class="delete-btn bg-msp-primary text-white px-4 py-2 rounded w-[5rem]" data-id="${widget.id}">Delete</button>
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
        });
    </script>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/footer.php"; ?>
</body>

</html>