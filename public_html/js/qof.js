// g javascript

function singleFileValidation(jInfo) {
	//target, file_extensions, mandatoryFile, size

	jInfo.mandatoryFile =
		jInfo.mandatoryFile == undefined ? 1 : jInfo.mandatoryFile;

	jInfo.errorMessages =
		jInfo.errorMessages == undefined
			? {
					m200: "",
					m201: "Fișierul este obligatoriu",
					m202: "Extensia nu este permisă",
					m203: "Fișier prea mare",
			  }
			: jInfo.errorMessages;

	const response = {
		check: true,
		status: 200, //201 - no file , 202 - bad extension , 203 - file too big
		message: "",
	};

	if (jInfo.mandatoryFile == 1 && jInfo.target.files.length == 0) {
		response.check = false;
		response.status = 201;
		response.message = jInfo.errorMessages["m" + response.status];
		return response;
	}

	if (jInfo.mandatoryFile == 0 && jInfo.target.files.length == 0) {
		response.check = true;
		response.status = 200;
		response.message = jInfo.errorMessages["m" + response.status];
		return response;
	}

	let ext = jInfo.target.files[0].name.split(".").pop(); // obtine extensia
	if (!jInfo.extensions.includes(ext)) {
		response.check = false;
		response.status = 202;
		response.message = jInfo.errorMessages["m" + response.status];
		return response;
	}

	let fileSize = jInfo.target.files[0].size;
	if (fileSize > jInfo.size) {
		response.check = false;
		response.status = 203;
		response.message = jInfo.errorMessages["m" + response.status];
		return response;
	}
	return response;
}

// g javascript

// mockups

function setHeight(master, e) {
	let dropdown = master.find(".jsCoH");
	if (e.type == "focusin") {
		let hmc = dropdown.attr("nrr"); //how many children
		let height = 0;
		dropdown.children(".csDEs").each(function (i) {
			if (i < hmc) {
				height += $(this).outerHeight(true);
			}
		});
		dropdown.css("height", height + "px");
	} else {
		dropdown.css("height", "0px");
	}
}

function searchField(subject) {
	var master = subject.closest(".jsDrpM");
	let srcEl = master.find(".jsCoH").find(".jsInfo");
	srcEl.map((i, element) => {
		if (
			$(element)
				.html()
				.toLowerCase()
				.includes(subject.val().trim().toLowerCase())
		) {
			$(element).parent().css("display", "grid");
		} else {
			$(element).parent().css("display", "none");
		}
	});
}

document.addEventListener("DOMContentLoaded", () => {
	let prevSrc;
	$(document).on("keyup", ".jsInput", function () {
		clearTimeout(prevSrc);
		prevSrc = setTimeout(() => {
			searchField($(this));
		}, 250);
	});

	$(document).on("focusin", ".jsDrpM", function (e) {
		setHeight($(this), e);
	});
	$(document).on("focusout", ".jsDrpM", function (e) {
		setHeight($(this), e);
	});

	$(document).on("click", ".jsDrpM .jsCoH > div", function (e) {
		let input = $(this).closest(".jsDrpM").find(".jsInput");
		input.val($(this).find(".jsInfo").html());
		input.attr("vall", $(this).find(".jsInfo").attr("vall"));
		input.trigger("change");
	});
});

//mockups

class dialog {
	constructor() {
		this.modals = {};
		this.page = document.querySelector("body");
	}

	newModal(settings = {}, style = {}) {
		let currentModal = {};
		style = this.defaultStyle(style);
		settings = this.defaultSettings(settings);
		style.id = settings.id;

		if (this.modals.hasOwnProperty(settings.id)) {
			console.log("Modal with this ID already Exists");
		} else {
			let backDrop = document.createElement("div"); //backdrop
			currentModal.modal = backDrop;
			backDrop.id = settings.id;

			let diagBody = document.createElement("div"); // main body
			diagBody.id = `DB` + settings.id;
			backDrop.appendChild(diagBody);

			diagBody.appendChild(this.upperBar(settings, style)); // UPPER BAR

			let contentBody = document.createElement("div");
			contentBody.id = `C` + settings.id;
			contentBody.className = `mContent`;
			contentBody.style.cssText = `${style.mContent}`;
			if (Array.isArray(settings.content)) {
				settings.content.map((e)=>{contentBody.appendChild(e)}); //prettier-ignore
			} else {
				contentBody.appendChild(settings.content);
			}

			diagBody.appendChild(contentBody);

			let actions = document.createElement("div");
			actions.id = `A` + settings.id;
			if (settings.actions instanceof Node)
				// Your variable is a Node
				actions.appendChild(settings.actions);

			diagBody.appendChild(actions);

			this.page.appendChild(backDrop); // apend it
			//if (settings.showByDefault == 1) // show it or not
			// MODIFY LATER

			let styleBody = this.addStyle(style); // add this specifyc style
			this.page.appendChild(styleBody);
			currentModal.styleHTML = styleBody;
			currentModal.style = style;
			currentModal.settings = settings;
			this.modals[settings.id] = currentModal;
		}
	}

	upperBar(settings, style) {
		let masterUpperBar = document.createElement("div");
		masterUpperBar.className = `mMUpper`;
		masterUpperBar.style.cssText = `position:relative;
                                  z-index:1;
                                  border-top-left-radius:${style.borderRadiusBod};
                                  border-top-right-radius:${style.borderRadiusBod};
                                  background:${style.bgColor};
                                  user-select:none;
                                  ${style.mUpper}`;

		let upperBar = document.createElement("div"); // upper side
		upperBar.className = `mUpper`;
		masterUpperBar.id = `Upper` + settings.id;
		upperBar.style.cssText = `
                                display: flex;
                                justify-content: center;
                                position: relative;
                                font-size: 1rem;
                                color: var(--white);
                                text-align: center;
                                padding-top: 1rem;
                                padding-bottom: 1rem;
                                ${style.eUpper}
                              `;

		masterUpperBar.appendChild(upperBar);
		let title = document.createElement("div"); // title element
		title.className = `mTitle`;
		title.style.cssText = `${style.tExtraCss}
                                                    max-width: 80%;
                                                    color:${style.tColor}
                                                     `;
		title.style.fontSize = "1.1rem";
		if (typeof settings.title === "string") {
			title.textContent = settings.title;
		} else {
			title.appendChild(settings.title);
		}

		upperBar.appendChild(title);
		let close = document.createElement("div"); // close button
		close.className = "xESBtn ";
		close.style.cssText = `
                                                    --thickness: 2px;
                                                    --length: 20px;
                                                    --_sizeBox: 40px;
                                                    display: block;
                                                    position: absolute;
                                                    cursor: pointer;
                                                    right: 0.5rem;
                                                    top: 50%;
                                                    transform:translateY(-50%);
                                                    font-size: 2.4rem;
                                                    width: var(--_sizeBox);
                                                    height: var(--_sizeBox);
                                                    border-radius:50%;
                                                    `;
		close.innerHTML = `<div style="position: absolute;
                                                            transform: translate(-50%,-50%) rotate(-45deg);
                                                            top: 50%;
                                                            left: 50%;
                                                            width: var(--length);
                                                            height: var(--thickness);
                                                            background: ${style.xBG};"></div>
                                                <div style="position: absolute;
                                                            transform: translate(-50%,-50%) rotate(45deg);
                                                            top: 50%;
                                                            left: 50%;
                                                            width: var(--length);
                                                            height: var(--thickness);
                                                            background: ${style.xBG};
                                                            border-radius: 8px;"></div>
                                                `;
		upperBar.appendChild(close);
		close.addEventListener("click", () => {
			this.removeModal(settings.id);
		});
		//close.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" d="M15 15L9 9m6 0l-6 6"></path><circle cx="12" cy="12" r="10"></circle></g></svg>`;

		return masterUpperBar;
	}

	showModal(id) {
		if (this.modals.hasOwnProperty(id)) {
			this.modals[id].modal.style.display = `block`;
			this.modals[id].modal.style.pointerEvents = `all`;
		}
	}
	hideModal(id) {
		if (this.modals.hasOwnProperty(id)) {
			this.modals[id].modal.style.display = `none`;
			this.modals[id].modal.style.pointerEvents = `none`;
		}
	}
	removeModal(id) {
		this.modals[id].settings.closeAction(); //do the close action
		this.modals[id].modal.remove();
		this.modals[id].styleHTML.remove();
		delete this.modals[id];
	}

	showList() {
		return this.modals;
	}

	defaultSettings(defaultSettings = {}) {
		defaultSettings.closeAction = defaultSettings.hasOwnProperty("closeAction")
			? defaultSettings.closeAction
			: () => {};
		defaultSettings.id = defaultSettings.hasOwnProperty("id")
			? defaultSettings.id
			: `dia` +
			  (new Date().getTime() +
					"" +
					Math.floor(Math.random() * (10 - 1 + 1)) +
					1);
		defaultSettings.showByDefault = defaultSettings.hasOwnProperty(
			"showByDefault"
		)
			? defaultSettings.showByDefault
			: 1;

		defaultSettings.title = defaultSettings.hasOwnProperty("title")
			? defaultSettings.title
			: "";

		return defaultSettings;
	}
	defaultStyle(defaultStyle = {}) {
		defaultStyle.extraCss = defaultStyle.hasOwnProperty("extraCss")
			? defaultStyle.extraCss
			: "";
		// STYLE SETTINGS
		//EI - EXTRA INFO ( like css settings and so on )

		defaultStyle.cMain = defaultStyle.hasOwnProperty("cMain")
			? defaultStyle.cMain
			: "";

		defaultStyle.aLower = defaultStyle.hasOwnProperty("aLower")
			? defaultStyle.aLower
			: "";
		defaultStyle.mUpper = defaultStyle.hasOwnProperty("mUpper")
			? defaultStyle.mUpper
			: "";
		defaultStyle.eUpper = defaultStyle.hasOwnProperty("eUpper")
			? defaultStyle.eUpper
			: "";
		defaultStyle.mContent = defaultStyle.hasOwnProperty("mContent")
			? defaultStyle.mContent
			: "";
		// size settings
		defaultStyle.width = defaultStyle.hasOwnProperty("width")
			? defaultStyle.width
			: "1160px";
		defaultStyle.tExtraCss = defaultStyle.hasOwnProperty("tExtraCss")
			? defaultStyle.tExtraCss
			: "";
		defaultStyle.DBExtraCss = defaultStyle.hasOwnProperty("DBExtraCss")
			? defaultStyle.DBExtraCss
			: "";

		// timer and closing functions
		defaultStyle.timer = defaultStyle.hasOwnProperty("timer")
			? defaultStyle.timer
			: 0;
		defaultStyle.duration = defaultStyle.hasOwnProperty("duration")
			? defaultStyle.duration
			: 0;
		defaultStyle.hideX = defaultStyle.hasOwnProperty("hideX")
			? defaultStyle.hideX
			: 0;
		defaultStyle.xBG = defaultStyle.hasOwnProperty("xBG")
			? defaultStyle.xBG
			: "black";

		// bind triggers for external uses
		defaultStyle.customTrigger = defaultStyle.hasOwnProperty("customTrigger")
			? defaultStyle.customTrigger
			: "";

		//title settings
		defaultStyle.tColor = defaultStyle.hasOwnProperty("tColor")
			? defaultStyle.tColor
			: "black";

		// body settings
		defaultStyle.borderRadiusBod = defaultStyle.hasOwnProperty(
			"borderRadiusBody"
		)
			? defaultStyle.borderRadiusBod
			: "8px";
		defaultStyle.paddingBod = defaultStyle.hasOwnProperty("paddingBod")
			? defaultStyle.paddingBod
			: "16px";
		defaultStyle.bgColor = defaultStyle.hasOwnProperty("bgColor")
			? defaultStyle.bgColor
			: "#272727";
		defaultStyle.dropShadow = defaultStyle.hasOwnProperty("dropShadow")
			? defaultStyle.dropShadow
			: "0 0.5rem 1rem rgba(0, 0, 0, 0.15)";

		//default

		//upper bar settings

		// actions bar settings
		defaultStyle.paddingActions = defaultStyle.hasOwnProperty("paddingActions")
			? defaultStyle.paddingActions
			: "8px";

		//scrollbar
		defaultStyle.trackBG = defaultStyle.hasOwnProperty("trackBG")
			? defaultStyle.trackBG
			: "rgba(0,0,0,0)";
		defaultStyle.thumbBG = defaultStyle.hasOwnProperty("thumbBG")
			? defaultStyle.thumbBG
			: "#4d4d4d";

		return defaultStyle;
	}
	addStyle(styleSheet) {
		let style = document.createElement("style");
		style.innerHTML = `
                        body{
                            overflow:hidden!important;
                        }
                        #${styleSheet.id}{
                        --content-max-width: ${styleSheet.width};
                        --padding-inline: 2rem;
                        --borderRadius:${styleSheet.borderRadiusBod};
                        --__height:100vh;
                        position: fixed;
                        top: 0px;
                        left: 0px;
                        height: var(--__height);
                        width:100%;
                        z-index: 5000000;
                        border:unset;
                        display: grid;
                        grid-template-rows: var(--__height);
                        align-items:start;
                        padding:0px;
                        background:rgb(0,0,0,0.5);

                        ${styleSheet.extraCss}
                        }
                        
                        #DB${styleSheet.id}{
                          --spaceUp: 2vh;
                          
                          display:grid;
                          grid-template-rows: auto 1fr auto;
                          max-height:calc(100% - var(--spaceUp) * 2);
                          width: min(100% - (var(--padding-inline) / 2), var(--content-max-width));
                          margin-top:var(--spaceUp);
                          margin-inline: auto;

                          box-shadow:${styleSheet.dropShadow};

                          animation:bounce${styleSheet.id};
                          animation-duration:500ms;
                          animation-timing-function:cubic-bezier(0.18, 0.89, 0.32, 1.05);
                          
                          ${styleSheet.DBExtraCss}
                        }

                        #C${styleSheet.id}{
                        background:${styleSheet.bgColor};
                        padding:${styleSheet.paddingBod};
                        overflow-y:auto;
                        ${styleSheet.cMain}
                        }

                        #C${styleSheet.id}::-webkit-scrollbar-track {
                            background-color: ${styleSheet.trackBG};
                            border-radius: 10px;
                        }

                        #C${styleSheet.id}::-webkit-scrollbar {
                            border-radius: 25px;
                            width: 4px;
                            height: 4px;
                        }

                        #C${styleSheet.id}::-webkit-scrollbar-thumb {
                            border-radius: 10px;
                            background-color: ${styleSheet.thumbBG};
                        }

                        #A${styleSheet.id}{
                        display:flex;
                        flex-direction:row;
                        justify-content:flex-end;
                        background:${styleSheet.bgColor};
                        padding:${styleSheet.paddingActions};
                        border-bottom-left-radius:${styleSheet.borderRadiusBod};
                        border-bottom-right-radius:${styleSheet.borderRadiusBod};
                        ${styleSheet.aLower}
                        }
                        

                        @-webkit-keyframes bounce${styleSheet.id} {
                            0% {
                                scale: 0;
                            }

                            100% {
                                scale: 1;
                            }
                        }
                        @media only screen and (max-width: 900px) {
                            #DB${styleSheet.id}{
                              height: 90%;
                            }
                        }                       
                    `;
		return style;
	}
}

document.addEventListener("DOMContentLoaded", () => {
	$(".flyout-content")
		.parent()
		.append('<span class="has_submenu"><i class="fas fa-plus"></i></span>');
	$(".flyout-content2")
		.parent()
		.append('<span class="has_submenu"><i class="fas fa-plus"></i></span>');

	$(".has_submenu").click(function () {
		$(this).siblings("ul").toggleClass("show_element");
		$(this).toggleClass("rotate");
	});

	$(".hamburger_trigger").click(function () {
		$(this).toggleClass("is-active");
		$(".menu_nav").toggleClass("show_element");
	});

	$(document).on("keydown", ".err input", function (e) {
		if (e.keyCode != 13) $(this).closest(".err").removeClass("err");
	});
	$(document).on("keydown", ".err2 input", function (e) {
		if (e.keyCode != 13) $(this).closest(".err2").removeClass("err2");
	});
	dialogs = new dialog();
});

function textError(parent) {
	let errElement = parent.find(".csErr");
	errElement.css("height", $(errElement.children()[0]).outerHeight());
}

function removeTextError(parent) {
	let errElement = parent.find(".csErr");
	errElement.css("height", "0");
}

function addShake(subject) {
	if (subject.hasClass("err")) {
		subject.removeClass("err");
		subject.addClass("err2");
	} else {
		subject.removeClass("err2");
		subject.addClass("err");
	}
}

function validate(field, formdata, k, details) {
	// 1 - normal input
	// 2 - dropdown input
	// 3 - phone number
	details.mode = details.mode || 1;
	let value = field.val().trim();
	let vall = field.attr("vall");
	if (details.mode == 1) {
		if (value.length == 0) {
			addShake(field.parent().parent());
			k.value += 1;
		} else {
			formdata.append(field.attr("id"), value);
		}
	}
	if (details.mode == 2) {
		if (vall != undefined && vall.length > 0) {
			formdata.append(field.attr("id"), value);
		} else {
			addShake(field.parent().parent());
			k.value += 1;
		}
	}

	if (details.mode == 3) {
		if (phoneRegex.test(value) && value.length > 8) {
			formdata.append(field.attr("id"), value);
		} else {
			addShake(field.parent().parent());
			let errElement = field.closest(".jsAnchor").find(".jsErr");
			errElement.css("height", $(errElement.children()[0]).outerHeight());
			k.value += 1;
		}
	}
}

function getVal(input, formdata) {
	formdata.append(input.attr("id"), input.val().trim());
}

function errPass(pass1, pass2) {
	addShake(pass1.parent().parent());
	addShake(pass2.parent().parent());
	let errElement = pass1.closest(".jsAnchor").find(".jsErr");
	errElement.css("height", $(errElement.children()[0]).outerHeight());
}

class popUpErrors {
	// pop Up Control Room Style
	constructor() {
		document.addEventListener("DOMContentLoaded", () => {
			let body = document.querySelector("body");
			this.animationDuration = 250;
			this.errorsBody = document.createElement("div");
			this.errorsBody.className = `markToasts dss errHolder`;
			this.errorsBody.style.cssText = ``;
			body.appendChild(this.errorsBody);

			let style = document.createElement("style");
			style.textContent = ``;

			body.appendChild(style);
		});
	}

	alertBody(info) {
		info.title = info.title || "";
		info.body = info.body || "";
		info.closeDuration = info.closeDuration || "10"; // how long till it closes automatically
		info.style = info.style || "";
		// @@===> style:
		// neutral / info / success / alert / error

		let body = this.bodyGen(info);

		let upper = document.createElement("div");
		upper.className = `errToastUpper`;
		upper.style.cssText = ` display: grid;
                                  grid-template-columns: var(--firstColumn) 1fr auto;
                                  padding: 10px;
                                  grid-column-gap:12px;
                                  `;

		let leftS = this.iconGen(info.style);

		let middleS = document.createElement("div");
		middleS.style.cssText = `padding-top:5px; color:var(--bgCo5);`;
		middleS.innerHTML = info.body;

		let rightS = this.xBtn();

		upper.appendChild(leftS);
		upper.appendChild(middleS);
		upper.appendChild(rightS);
		body.appendChild(upper);

		let lower = document.createElement("div");
		lower.className = `fillStrip`;
		lower.style.cssText = `height:3px; width:100%;`;
		body.appendChild(lower);

		let masterBody = document.createElement("div");
		masterBody.style.cssText = `
                  height:0px;
                  position:relative; 
                  transition:250ms ease-out;
                  margin-top:10px;
                  overflow:hidden;
                  `;
		masterBody.appendChild(body);

		this.errorsBody.appendChild(masterBody); // append to error holder
		masterBody.style.height = body.offsetHeight + "px";
		setTimeout(() => {
			masterBody.style.removeProperty("height");
			masterBody.firstElementChild.style.position = "relative";
		}, 250);

		rightS.addEventListener("click", () => {
			this.removeElement(masterBody);
		});

		let resumeTimer;
		var left = 0;
		resumeTimer = setInterval(() => {
			if (info.closeDuration - left == 0) {
				this.removeElement(masterBody, rightS);
				window.clearInterval(resumeTimer);
			} else {
				left++;
			}
		}, 1000);
	}

	bodyGen(info) {
		let body = document.createElement("div");
		//bgCo1 - background color 1 ( 1 the darkest , 5 the lightest )

		let classes = "";
		let css = ``;
		switch (info.style) {
			case "neutral":
				classes = ``;
				css = ` --bgCo1:#272727;
                          --bgCo2:#434343;
                          --bgCo3:#616161;
                          --bgCo4:#7E7E7E;
                          --bgCo5:#b3b3b3;
                          `;
				break;
			case "info":
				classes = ``;
				css = `--bgCo1:#030C16;
                          --bgCo2:#071C34;
                          --bgCo3:#0B2C51;
                          --bgCo4:#0F3C6E;
                          --bgCo5:#8fbcef;
                          `;
				break;
			case "success":
				classes = ``;
				css = ` --bgCo1:#1A431A;
                          --bgCo2:#256125;
                          --bgCo3:#307E30;
                          --bgCo4:#3B9B3B;
                          --bgCo5:#46B846;
                          `;
				break;
			case "alert":
				classes = ``;
				css = ` --bgCo1:#59420D;
                          --bgCo2:#a77e1c;
                          --bgCo3:#e4ab24;
                          --bgCo4:#B1841B;
                          --bgCo5:#ecc979;
                          `;
				break;
			case "error":
				classes = ``;
				css = ` --bgCo1:#461611;
                          --bgCo2:#971609;
                          --bgCo3:#dd7d72;
                          --bgCo4:#dd5647;
                          --bgCo5:#f44936;
                              `;
				break;
			default:
				classes = ``;
				css = ` --bgCo1:#272727;
                          --bgCo2:#434343;
                          --bgCo3:#616161;
                          --bgCo4:#7E7E7E;
                          --bgCo5:#b3b3b3;
                          `;
		}
		body.className = " " + classes;
		body.style.cssText =
			` position:absolute;
              display: grid;
              grid-template-rows: 1fr auto;
              grid-column-gap:5px;
              min-height: 30px;
              width:100%;
              background-color:var(--bgCo1);
              color: white;
              pointer-events: all;
              border-radius:4px;
              overflow:hidden;
              --firstColumn:45px;
              --secondsAnimation:${info.closeDuration}s;
                  ` + css;

		return body;
	}
	iconGen(style) {
		let icon = document.createElement("div");
		let classes = "";
		let css = ``;
		switch (style) {
			case "neutral":
				classes = `icon icon-info-circle`;
				css = ``;
				break;
			case "info":
				classes = `icon icon-info-circle`;
				css = ``;
				break;
			case "success":
				classes = `icon icon-check-circle`;
				css = ``;
				break;
			case "alert":
				classes = `icon icon-alert-triangle`;
				css = ``;
				break;
			case "error":
				classes = `icon icon-alert-triangle`;
				css = ``;
				break;
			default:
				classes = `icon icon-info-circle`;
				css = ``;
		}
		icon.className = "  " + classes;
		icon.style.cssText =
			`  font-size:1.5rem;
                                                border-radius:50%;
                                                aspect-ratio:1/1;
                                                padding:8px;
                                                background-color:var(--bgCo4);
                                                color:var(--bgCo1);
                                            ` + css;
		return icon;
	}
	xBtn() {
		let rightS = document.createElement("div"); // close button
		rightS.style.cssText = `--thickness: 3px;
                                  --length: 20px;
                                  --_sizeBox: 25px;
                                  --_bgCol: var(--bgCo5);
                                  display: block;
                                  position: relative;
                                  cursor: pointer;
                                  font-size: 2.4rem;
                                  width: var(--_sizeBox);
                                  height: var(--_sizeBox);
                                  border-radius: 50%;
                                  `;
		rightS.innerHTML = `<div style="position: absolute;
                                          transform: translate(-50%,-50%) rotate(-45deg);
                                          top: 50%;
                                          left: 50%;
                                          width: var(--length);
                                          height: var(--thickness);
                                          background: var(--_bgCol);
                                          border-radius: 8px;"></div>
                              <div style="position: absolute;
                                          transform: translate(-50%,-50%) rotate(45deg);
                                          top: 50%;
                                          left: 50%;
                                          width: var(--length);
                                          height: var(--thickness);
                                          background: var(--_bgCol);
                                          border-radius: 8px;"></div>`;
		return rightS;
	}
	removeElement(subject) {
		subject.style.height = subject.firstElementChild.offsetHeight + "px";
		subject.firstElementChild.style.position = "absolute";

		setTimeout(() => {
			subject.style.height = 0;
			subject.style.marginTop = 0;
			setTimeout(() => {
				subject.remove();
			}, 350);
		}, 100);
	}
}
let errors = new popUpErrors();

let dialogs;
