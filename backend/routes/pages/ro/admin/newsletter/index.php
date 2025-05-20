<?php
include "../" . $_ENV["BACKEND"] . "/classes/seo.php";
$currentPath = $_ENV["CURRENT_PATH"];
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["LANG"]; ?>">

<head>
    <?php
    require "../" . $_ENV["BACKEND"] . "/resources/components/head.php"; ?>

    <style>
        .srcInpt {
            position: relative;
            display: block;
            width: 100%;
            min-width: 50px;
        }

        .sortBtn {
            word-break: keep-all;
            user-select: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/nav.php"; ?>

    </div>
    <div class="container flex flex-col px-6 mt-[50px] mb-[100px] mx-auto items-center justify-start md:gap-4 lg:px-4 xlg:max-w-full">
        <div id="newsletter-tabs" class="flex w-full"></div>
        <button id="addNewsletterBtn" class="bg-green-500 text-white px-4 py-2 rounded mb-4">Add New Newsletter</button>
        <div style="width:100%;">
            <div style="overflow-y: auto;">
                <table id="newsletters-table" class="table-auto w-full border-collapse border">
                    <thead>
                        <tr>
                            <th class="border p-2">Actions</th>
                            <th class="border p-2">
                                <div style="display:flex; grid-gap:10px;">
                                    <input type="text" placeholder="Email" data-sort="email" class="srcInpt">
                                    <div class="sortBtn" data-sort="email">ASC</div>
                                </div>
                            </th>
                            <th class="border p-2">
                                <div style="display:flex; grid-gap:10px;">
                                    <input type="text" placeholder="Name" data-sort="nume" class="srcInpt">
                                    <div class="sortBtn" data-sort="nume">ASC</div>
                                </div>
                            </th>
                            <th class="border p-2">
                                <div style="display:flex; grid-gap:10px;">
                                    <input type="text" placeholder="Phone" data-sort="telefon" class="srcInpt">
                                    <div class="sortBtn" data-sort="telefon">ASC</div>
                                </div>
                            </th>
                            <th class="border p-2">
                                <div style="display:flex; grid-gap:10px;">
                                    <input type="text" placeholder="Domain" data-sort="domeniu" class="srcInpt">
                                    <div class="sortBtn" data-sort="domeniu">ASC</div>
                                </div>
                            </th>
                            <th class="border p-2">
                                <div style="display:flex; grid-gap:10px;">
                                    <input type="text" placeholder="Message" data-sort="mesaj" class="srcInpt">
                                    <div class="sortBtn" data-sort="mesaj">ASC</div>
                                </div>
                            </th>
                            <th class="border p-2">
                                <div style="display:flex; grid-gap:10px;">
                                    <input type="text" placeholder="Publish Date" data-sort="publish_date" class="srcInpt">
                                    <div class="sortBtn" data-sort="publish_date">ASC</div>
                                </div>
                            </th>
                            <th class="border p-2">
                                <div style="display:flex; grid-gap:10px;">
                                    <input type="text" placeholder="Source" data-sort="source" class="srcInpt">
                                    <div class="sortBtn" data-sort="source">ASC</div>
                                </div>
                            </th>

                        </tr>
                    </thead>
                    <tbody id="newsletter-list">
                    </tbody>
                </table>

            </div>
        </div>


    </div>

    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/global_script.php"; ?>
    <script>
        const controllerPath = '<?php echo $currentPath ?>/api/newsletter';


        $(document).ready(function() {
            let newPop;
            let allNewsletters = undefined;
            let liveNewsletters = undefined;
            let sortDirection = "asc";
            let sortBy = undefined;
            let preventSpam;

            /**
             * Filter an array of objects by a given key.
             *
             * @param {Array<Object>} data         – The array to filter.
             * @param {string}        key          – The object property to filter by.
             * @param {string|number|RegExp|Function} criterion
             *        – If a string: performs a case-insensitive substring match.
             *        – If a number: performs == comparison.
             *        – If a RegExp: tests the property with .test().
             *        – If a Function: called as fn(value, item) and should return true/false.
             * @returns {Array<Object>}            – The filtered array.
             *  // 1. Find all newsletters whose title contains “study” (case-insensitive substring)
                const studies = filterByColumn(newsletters, 'title', 'study');

                // 2. Find all issue‐1 newsletters (exact match numeric or string)
                const issue1 = filterByColumn(newsletters, 'issue', 1);

                // 3. Find all where keywords mention “tourism” (substring)
                const tourismPapers = filterByColumn(newsletters, 'keywords', 'tourism');

                // 4. Use a regex to match publication dates in 2022
                const pubs2022 = filterByColumn(newsletters, 'publish_date', /^2022/);

                // 5. Custom: only newsletters published after Jan 1, 2022
                const after2022 = filterByColumn(newsletters, 'publish_date', val => {
                return new Date(val) > new Date('2022-01-01');
                });
             */
            function filterByColumn(data, key, criterion) {
                return data.filter(item => {
                    const val = item[key];

                    // Handle missing values
                    if (val == null) return false;

                    // Custom function
                    if (typeof criterion === 'function') {
                        return criterion(val, item);
                    }

                    // Regex match
                    if (criterion instanceof RegExp) {
                        return criterion.test(val);
                    }

                    // Number match
                    if (typeof criterion === 'number' || typeof val === 'number') {
                        return val == criterion;
                    }

                    // String match (substring, case-insensitive)
                    const strVal = String(val).toLowerCase();
                    const strCrit = String(criterion).toLowerCase();
                    return strVal.includes(strCrit);
                });
            }

            function sortTable(dataset, value) {
                // console.log(liveNewsletters);
                const sorted = sortByColumn(dataset, value, sortDirection);
                return sorted;
            }


            $(document).on("click", ".sortBtn", function() {
                $(".sortBtn").html('<div data-icon="" class="icon text-2xl"></div>');
                $(this).html(sortDirection);
                sortBy = this.dataset.sort;
                sortDirection = sortDirection == "asc" ? "desc" : "asc";
                clearTimeout(preventSpam);
                preventSpam = setTimeout(() => {
                    renderNewsletters(liveNewsletters);
                }, 250);


            });

            $(document).on("keyup", ".srcInpt", function() {
                clearTimeout(preventSpam);
                preventSpam = setTimeout(() => {
                    renderNewsletters(liveNewsletters);
                }, 250);
            });



            function loadNewsletters() {
                $('#newsletter-list').html('');

                $.ajax({
                    url: controllerPath,
                    type: 'POST',
                    data: {
                        action: 'getAll'
                    },
                    success: function(response) {
                        let newsletters = JSON.parse(response);
                        liveNewsletters = newsletters;
                        allNewsletters = newsletters;
                        renderNewsletters(newsletters);
                    }
                });
            }
            loadNewsletters();

            function makeCrud(jInfo) {
                const leftFields = [{
                        id: "email",
                        label: "Email",
                        type: "email"
                    },
                    {
                        id: "nume",
                        label: "Name",
                        type: "text"
                    },
                    {
                        id: "telefon",
                        label: "Phone",
                        type: "tel"
                    },
                    {
                        id: "domeniu",
                        label: "Domain",
                        type: "text"
                    },
                ];

                const rightFields = [{
                    id: "publish_date",
                    label: "Publish Date",
                    type: "datetime-local"
                }];

                const selectFields = [{
                    id: "source",
                    label: "Source",
                    options: [{
                        value: "newsletter-footer",
                        text: "Footer"
                    }, {
                        value: "newsletter-home",
                        text: "Home page"
                    }, {
                        value: "newsletter-contact",
                        text: "Contact page"
                    }]
                }];

                let leftHTML = leftFields.map(field => `
                    <div class="relative">
                        <input id="edit-${field.id}" type="${field.type}" ${field.required &&  !("id" in jInfo)  ? "required":""} name="${field.id}" 
                            class="peer leading-8 mb-4 block border p-2 px-4 rounded w-full placeholder-transparent focus:outline-none focus:ring-2 focus:ring-black"
                            placeholder="" value="${jInfo[field.id] || ''}"/>
                        <label for="${field.id}" class="absolute left-0 ml-2 -top-2.5 bg-white px-2 text-gray-600
                            duration-200 transform scale-100 origin-[0] 
                            peer-placeholder-shown:top-2.5 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 
                            peer-focus:-top-2.5 peer-focus:text-black">${field.label}</label>
                    </div>
                `).join('');

                let rightHTML = rightFields.map(field => {
                    let value = jInfo[field.id] || '';

                    if (field.id === "publish_date" && value.includes(" ")) {
                        const [date, time] = value.split(" ");
                        value = `${date}T${time.slice(0, 5)}`; // T + HH:MM
                    }

                    return `
        <div class="relative">
            <input id="edit-${field.id}" type="${field.type}" name="${field.id}" 
                class="peer leading-8 mb-4 block border p-2 px-4 rounded w-full placeholder-transparent focus:outline-none focus:ring-2 focus:ring-black"
                placeholder="" value="${value}"/>
            <label for="${field.id}" class="absolute left-0 ml-2 -top-2.5 bg-white px-2 text-gray-600
                duration-200 transform scale-100 origin-[0] 
                peer-placeholder-shown:top-2.5 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 
                peer-focus:-top-2.5 peer-focus:text-black">${field.label}</label>
        </div>
    `;
                }).join('');

                let selectHTML = selectFields.map(field => `
                    <div class="relative">
                        <select id="edit-${field.id}" name="${field.id}" class="peer border p-2 leading-8 mb-4 px-4 rounded w-full appearance-none bg-white focus:outline-none focus:ring-2 focus:ring-black">
                            ${field.options.map(option => 
                                `<option value="${typeof option === 'object' ? option.value : option}" 
                                    ${jInfo[field.id] == (typeof option === 'object' ? option.value : option) ? 'selected' : ''}>
                                    ${typeof option === 'object' ? option.text : field.label+' ' + option}
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
                    <form id="edit-newsletter-form" class="flex flex-col w-full gap-4">
                        <div class="flex gap-4 flex-col md:flex-row">
                            <div class="flex flex-col w-full md:w-1/2">
                                <input type="hidden" id="edit-newsletter-id" name="id" value="${jInfo.id ? jInfo.id : ''}">
                                ${leftHTML}
                            </div>
                           <div class="flex flex-col w-full md:w-1/2">
                                ${rightHTML}
                                ${selectHTML}
                            </div>
                        </div>
                        <div class="relative">
                            <textarea rows="8" id="edit-mesaj" name="mesaj" 
                                class="peer block border p-4 rounded w-full placeholder-transparent focus:outline-none focus:ring-2 focus:ring-black" 
                                placeholder="">${jInfo.mesaj || ''}</textarea>
                            <label for="mesaj" class="absolute left-0 ml-2 -top-2.5 bg-white px-2 text-gray-600
                                duration-200 transform scale-100 origin-[0] 
                                peer-placeholder-shown:top-2.5 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 
                                peer-focus:-top-2.5 peer-focus:text-black">Message</label>
                        </div>
                        <input type="hidden" name="form_time" value="<?= time(); ?>">
                        <input type="hidden" id="crud-form" name="crud-form" value="crud-form">
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                            <button type="button" id="cancel-btn" class="bg-gray-400 text-white px-4 py-2 rounded ml-2">Cancel</button>
                        </div>
                    </form>
                `;

                document.body.appendChild(crudBody);



                newPop = new popUps({
                    title: document.createTextNode(jInfo.id ? "Edit Newsletter" : "Add New Newsletter"),
                    content: crudBody
                });
                newPop.htmlContent.style.cssText += `background:white; border:1px solid rgba(255,255,255,0.2);`;
                newPop.htmlClose.style.cssText += `--_color:black;`;
                newPop.htmlUpBar.classList.add("flex", "w-full", "items-center", "font-bold", "text-2xl", "py-4", "justify-center", "text-black");


                const cancelButton = crudBody.querySelector("#cancel-btn");
                cancelButton.addEventListener("click", () => {
                    newPop.remove();
                });
                newPop.show();
            }

            $(document).on("submit", "#edit-newsletter-form", function(e) {
                e.preventDefault();

                // const formData = $(this).serialize() + "&action=save";
                const formData = new FormData(document.getElementById('edit-newsletter-form'));
                formData.append("action", "save");

                $.ajax({
                    url: controllerPath,
                    type: "POST",
                    data: formData,
                    processData: false, // Don't let jQuery process the data
                    contentType: false, // Don't set a content type, let the browser do it
                    success: function(response) {
                        response = JSON.parse(response);
                        console.log(response);
                        if (response.status == 200) {
                            errors.alertBody({
                                body: "Newsletter saved successfully!",
                                style: "info",
                                closeDuration: 10,
                            });

                            loadNewsletters();
                        } else {
                            errors.alertBody({
                                body: "Error saving newsletter.",
                                style: "error",
                                closeDuration: 10,
                            });
                        }
                    },
                    error: function() {
                        errors.alertBody({
                            body: "Error saving newsletter.",
                            style: "error",
                            closeDuration: 10,
                        });
                    },
                });
            });

            $('#addNewsletterBtn').click(function() {
                makeCrud({});
            });

            $(document).on("click", ".edit-btn", function() {
                const newsletterId = $(this).data("id");

                $.ajax({
                    url: controllerPath,
                    type: "POST",
                    data: {
                        action: "getById",
                        id: newsletterId
                    },
                    success: function(response) {
                        const newsletter = JSON.parse(response);
                        makeCrud(newsletter);
                    },
                    error: function() {
                        errors.alertBody({
                            body: "Error saving newsletter.",
                            style: "error",
                            closeDuration: 10,
                        });
                    },
                });
            });

            $(document).on("click", ".delete-btn", function() {
                const newsletterId = $(this).data("id");

                if (confirm("Are you sure you want to delete this newsletter?")) {
                    $.ajax({
                        url: controllerPath,
                        type: "POST",
                        data: {
                            action: "delete",
                            id: newsletterId
                        },
                        success: function() {
                            errors.alertBody({
                                body: "Newsletter deleted successfully!",
                                style: "info",
                                closeDuration: 10,
                            });

                            loadNewsletters();
                        },
                        error: function() {
                            errors.alertBody({
                                body: "Error deleting newsletter.",
                                style: "error",
                                closeDuration: 10,
                            });
                        },
                    });
                }
            });

            function renderNewsletters(newsletters) {
                clearTimeout(preventSpam);
                preventSpam = setTimeout(() => {
                    liveNewsletters = allNewsletters; //always start a fresh search
                    $(".srcInpt").map((i, e) => {
                        if (e.value.trim().length > 0) {
                            liveNewsletters = filterByColumn(liveNewsletters, e.dataset.sort, e.value.trim());
                        }
                    });
                    liveNewsletters = sortTable(liveNewsletters, sortBy);

                    let html = ``;
                    // Loop through all newsletters and display them in a single table
                    liveNewsletters.forEach(newsletter => {
                        const email = newsletter.email || 'No Email';
                        const nume = newsletter.nume || 'Unknown Nume';
                        const telefon = newsletter.telefon || 'N/A';
                        const domeniu = newsletter.domeniu || 'N/A';
                        const mesaj = newsletter.mesaj || 'N/A';
                        const publishDate = newsletter.publish_date || 'N/A';
                        const source = newsletter.source || 'N/A';

                        html += `
                    <tr>
                        <td class="border p-2">
                            <div class="flex flex-col h-full w-full items-center gap-2">
                                <button class="edit-btn bg-msp-accent text-white px-4 py-2 rounded w-[5rem]" data-id="${newsletter.id}">Edit</button>
                                <button class="delete-btn bg-msp-primary text-white px-4 py-2 rounded w-[5rem]" data-id="${newsletter.id}">Delete</button>
                            </div>
                        </td>
                        <td class="border p-2">${email}</td>
                        <td class="border p-2">${nume}</td>
                        <td class="border p-2">${telefon}</td>
                        <td class="border p-2">${domeniu}</td>
                        <td class="border p-2">${mesaj}</td>
                        <td class="border p-2">${publishDate}</td>
                        <td class="border p-2">${source}</td>
                    </tr>`;
                    });
                    html += ``;
                    $('#newsletter-list').html(html);
                }, 250);




            }
            // Filter table function for search
        });

        function sortByColumn(data, key, order = 'asc') {
            // copy so we don't mutate original
            const arr = [...data];

            arr.sort((a, b) => {
                let valA = a[key];
                let valB = b[key];

                // handle missing values
                if (valA == null && valB == null) return 0;
                if (valA == null) return order === 'desc' ? -1 : 1;
                if (valB == null) return order === 'desc' ? 1 : -1;

                // detect ISO date strings (YYYY-MM-DD)
                const isoDateRegex = /^\d{4}-\d{2}-\d{2}$/;
                if (typeof valA === 'string' && isoDateRegex.test(valA) &&
                    typeof valB === 'string' && isoDateRegex.test(valB)) {
                    valA = new Date(valA);
                    valB = new Date(valB);
                }
                // detect numeric strings vs numbers
                else if (!isNaN(valA) && !isNaN(valB)) {
                    valA = parseFloat(valA);
                    valB = parseFloat(valB);
                }
                // otherwise, leave as strings

                // compare
                if (valA < valB) return order === 'desc' ? -1 : 1;
                if (valA > valB) return order === 'desc' ? 1 : -1;
                return 0;
            });

            return arr;
        }
    </script>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/footer.php"; ?>
</body>

</html>