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
        <div id="contact-tabs" class="flex w-full"></div>
        <button id="addContactBtn" class="bg-green-500 text-white px-4 py-2 rounded mb-4 cursor-pointer">Add New Contact</button>
        <div id="contact-list"></div>
    </div>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/global_script.php"; ?>
    <script>
        const controllerPath = currentPath + '/api/contact';


        $(document).on('submit', '#edit-contact-form', function(e) {
            e.preventDefault();
            handleContactSubmit(e);
        });

        // Fetch version of loadContacts()
        function loadContacts() {
            const formData = new FormData();
            formData.append("action", "getAll");

            document.getElementById("contact-list").innerHTML = "<p>Loading...</p>";

            fetch(controllerPath, {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(contacts => {
                    if (!Array.isArray(contacts)) contacts = [];
                    if (contacts.length === 0) {
                        document.getElementById("contact-list").innerHTML = "<p>No contacts found.</p>";
                    } else {
                        renderContacts(contacts);
                    }
                })
                .catch(err => {
                    console.error("Failed to load contacts:", err);
                    document.getElementById("contact-list").innerHTML = "<p>Error loading contacts.</p>";
                });
        }

        // Fetch version of form submit handler
        function handleContactSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append("action", "save");

            fetch(controllerPath, {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message || "Contact saved successfully!");
                    loadContacts();
                })
                .catch(err => {
                    alert("Error saving contact.");
                    console.error(err);
                });
        }

        // Fetch version of delete
        function deleteContact(id) {
            const formData = new FormData();
            formData.append("action", "delete");
            formData.append("id", id);

            fetch(controllerPath, {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message || "Contact deleted successfully!");
                    loadContacts();
                })
                .catch(err => {
                    alert("Error deleting contact.");
                    console.error(err);
                });
        }

        // Fetch version of getById (edit)
        function editContact(id) {
            const formData = new FormData();
            formData.append("action", "getById");
            formData.append("id", id);

            fetch(controllerPath, {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(contact => {
                    makeCrud(contact);
                })
                .catch(err => {
                    alert("Error fetching contact.");
                    console.error(err);
                });
        }

        // Init
        window.addEventListener("DOMContentLoaded", () => {
            loadContacts();

            document.body.addEventListener("submit", e => {
                if (e.target && e.target.id === "edit-contact-form") {
                    handleContactSubmit(e);
                }
            });

            document.body.addEventListener("click", e => {
                if (e.target.matches("#addContactBtn")) {
                    makeCrud({});
                } else if (e.target.matches(".edit-btn")) {
                    editContact(e.target.dataset.id);
                } else if (e.target.matches(".delete-btn")) {
                    const id = e.target.dataset.id;
                    if (confirm("Are you sure you want to delete this contact?")) {
                        deleteContact(id);
                    }
                }
            });
        });

        function renderContacts(contacts) {
            let html = `<h2 class="font-raleway font-semibold text-black text-[17px] mb-[20px] text-left w-full text-center">Contacts</h2>`;

            html += `<input type="text" id="search" class="mb-4 p-2 w-full border border-gray-300 rounded" placeholder="Search contacts...">
             <div class="overflow-x-auto">
                <table id="contacts-table" class="table-auto w-full border-collapse border">
                    <thead>
                        <tr>
                            <th class="border p-2">Email</th>
                            <th class="border p-2">Nume</th>
                            <th class="border p-2">Telefon</th>
                            <th class="border p-2">Domeniu</th>
                            <th class="border p-2">Mesaj</th>
                            <th class="border p-2">Status</th>
                            <th class="border p-2">Added</th>
                            <th class="border p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>`;

            // Loop through all contacts and display them in a single table
            contacts.forEach(contact => {
                const email = contact.email || 'Not set';
                const nume = contact.nume || 'Not set';
                const telefon = contact.telefon || 'Not set';
                const domeniu = contact.domeniu || 'Not set';
                const mesaj = contact.mesaj || 'Not set';
                const publish_date = contact.publish_date || 'Not set';
                const status = contact.status === "0" ? 'Dezabonat' : 'Abonat';

                html += `
                <tr>
                    <td class="border p-2">${email}</td>
                    <td class="border p-2">${nume}</td>
                    <td class="border p-2">${telefon}</td>
                    <td class="border p-2">${domeniu}</td>
                    <td class="border p-2">${mesaj}</td>
                    <td class="border p-2">${status}</td>
                    <td class="border p-2">${publish_date}</td>
                    <td class="border p-2">
                        <div class="flex flex-col h-full w-full items-center gap-2">
                            <button class="edit-btn bg-msp-accent text-white px-4 py-2 rounded w-[5rem] cursor-pointer" data-id="${contact.id}">Edit</button>
                            <button class="delete-btn bg-msp-primary text-white px-4 py-2 rounded w-[5rem] cursor-pointer" data-id="${contact.id}">Delete</button>
                        </div>
                    </td>
                </tr>`;
            });

            html += `</tbody></table></div>`;
            $('#contact-list').html(html);

            // Add event listener to search input
            document.getElementById("search").addEventListener("input", function() {
                filterTable(this.value);
            });
        }

        function makeCrud(jInfo) {
            const leftFields = [{
                    id: "email",
                    label: "Email",
                    type: "email"
                },
                {
                    id: "nume",
                    label: "Nume",
                    type: "text"
                },
                {
                    id: "telefon",
                    label: "Telefon",
                    type: "text"
                },
                {
                    id: "domeniu",
                    label: "Domeniu",
                    type: "text"
                },
                {
                    id: "mesaj",
                    label: "Mesaj",
                    type: "text"
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
                    text: "Abonat"
                }, {
                    value: 0,
                    text: "Dezabonat"
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
                    <form id="edit-contact-form" enctype="multipart/form-data" class="flex flex-col w-full gap-4">
                        <div class="flex gap-4 flex-col md:flex-row">
                            <div class="flex flex-col w-full md:w-1/2">
                                <input type="hidden" id="edit-contact-id" name="id" value="${jInfo.id ? jInfo.id : ''}">
                                ${inputsHTML}
                                ${selectHTML}
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

            const newPop = new popUps({
                title: document.createTextNode(jInfo.id ? "Edit Contact" : "Add New Contact"),
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
        $('#addContactBtn').click(function() {
            makeCrud({});
        });
        // Filter table function for search
        function filterTable(query) {
            const rows = document.querySelectorAll("#contacts-table tbody tr");
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
    </script>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/footer.php"; ?>
</body>

</html>