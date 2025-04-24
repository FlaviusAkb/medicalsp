class popUps {
	// pop Up Control Room Style
	constructor(givenInfo = {}) {
		this.html = document.querySelectorAll("body")[0]; // get html body
		// prepare json values - start
		let fInfo = {
			title: document.createTextNode(""),
			content: document.createTextNode(""),
			controls: document.createTextNode(""),
			extra: {
				append: true, // if you dont want to append it by default set it to false
				bgExit: true,
				btnExit: true,
				css: ``,
			},
			cta: {
				remove: () => {},
				show: () => {},
				hide: () => {},
				clsBtn: () => {
					this.remove();
				},
				// clsBtn:
				// 	givenInfo?.cta?.clsBtn ||
				// 	(() => {
				// 		this.remove();
				// 	}),
			},
		};
		// Merge nested css properties, if provided.
		fInfo = {
			...fInfo,
			...givenInfo,
			extra: {
				...fInfo.extra,
				...givenInfo.extra,
			},
			cta: {
				...fInfo.cta,
				...givenInfo.cta,
			},
		};
		this.fInfo = fInfo;
		// prepare json values - end

		this.create();
	}
	create() {
		this.cStyle(); // create style element

		this.htmlFill = document.createElement("div"); // background - separate from the other elements
		this.htmlFill.style.cssText = `opacity:0; pointer-events:none; display:block; position:fixed; left:0px; top:0px; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:10000;`;

		// master content
		this.htmlMaster = document.createElement("div"); // the actual background that helps center things
		this.htmlMaster.className = "htmlMaster";
		this.htmlMaster.style.cssText = `opacity:0; pointer-events:none; --gBR: 14px; display: grid; grid-template-columns: 1fr; align-items: center; justify-items: center; position: fixed; left: 0px; top: 0px; width: 100vw; height: 100vh; z-index: 10001; overflow: auto; cursor: pointer; `;

		if (this.fInfo.extra.bgExit) {
			// if you can exist on fill bg click or not
			this.htmlMaster.addEventListener("click", (e) => {
				// close when clicking on background
				if (e.target === this.htmlMaster) this.fInfo.cta.clsBtn();
			});
		} else {
			this.htmlMaster.style.cursor = `default`;
		}

		// actual content
		this.htmlContent = document.createElement("div");
		this.htmlMaster.appendChild(this.htmlContent);
		this.htmlContent.className = `htmlContent`;
		this.htmlContent.style.cssText = ` display: block; width: min(1200px, 90%); background: #092c01; border-radius: var(--gBR); cursor: default; `;

		// up bar - start
		this.htmlUpBar = document.createElement("div");
		this.htmlContent.appendChild(this.htmlUpBar);
		this.htmlUpBar.className = `htmlUpBar`;
		this.htmlUpBar.style.cssText = ` position:relative; min-height:50px; padding:8px 12px; `;

		this.htmlUpBar.appendChild(this.fInfo.title); //set title
		this.htmlUpBar.appendChild(this.closeBtn()); // add close btn
		// up bar - end

		// middle content - start
		this.htmlMid = document.createElement("div");
		this.htmlContent.appendChild(this.htmlMid);
		this.htmlMid.className = `htmlMid`;
		this.htmlMid.style.cssText = ` position:relative; min-height:50px; padding:8px 12px; `;
		this.htmlMid.appendChild(this.fInfo.content); //set content
		// middle content - end

		// lower bar - start
		this.htmlLower = document.createElement("div");
		this.htmlContent.appendChild(this.htmlLower);
		this.htmlLower.className = `htmlLower`;
		this.htmlLower.style.cssText = ` position:relative; min-height:50px; padding:8px 12px; `;
		this.htmlLower.appendChild(this.fInfo.controls); //set content
		// lower bar - end

		if (this.fInfo.extra.append) {
			this.append();
		}
	}

	show() {
		this.fInfo.cta.show();
		this.htmlFill.style.opacity = "1";
		this.htmlMaster.style.pointerEvents = "all";
		this.htmlMaster.style.opacity = "1";
	}
	hide() {
		this.fInfo.cta.hide();
		this.htmlFill.style.pointerEvents = "none";
		this.htmlFill.style.opacity = "0";
		this.htmlMaster.style.pointerEvents = "none";
		this.htmlMaster.style.opacity = "0";
	}

	remove() {
		this.fInfo.cta.remove();
		this.htmlFill.remove();
		this.htmlMaster.remove();
		this.oneStyle.remove();
	}
	append(action = "append") {
		this.html.appendChild(this.oneStyle);
		this.html.appendChild(this.htmlFill);
		this.html.appendChild(this.htmlMaster);
	}

	closeBtn() {
		let close = document.createElement("div"); // close button
		close.className = "htmlClose";
		close.style.cssText = `--_sizeBox: 40px; --length: 20px; --thickness: 2px; --_color: white; --xRoundness: 8px; --boxRadius: 50%; display: block; position: absolute; top: calc(var(--_sizeBox) * 0.2); right: calc(var(--_sizeBox) * 0.2); width: var(--_sizeBox); height: var(--_sizeBox); border-radius: var(--_sizeBox); cursor: pointer;`;
		close.innerHTML = `   <div style="position: absolute; transform: translate(-50%, -50%) rotate(-45deg); top: 50%; left: 50%; width: var(--length); height: var(--thickness); background: var(--_color); border-radius: var(--_sizeBox);" ></div>
                              <div style="position: absolute; transform: translate(-50%, -50%) rotate(45deg); top: 50%; left: 50%; width: var(--length); height: var(--thickness); background: var(--_color); border-radius: var(--_sizeBox);" ></div> `;
		close.addEventListener("click", () => {
			this.fInfo.cta.clsBtn();
		});
		if (!this.fInfo.extra.btnExit) {
			close.style.display = "none";
		}
		return (this.htmlClose = close);
	}

	cStyle() {
		// add custom css style for elements if desired
		const rng = this.rng(10);
		const popStyle = document.getElementById(rng);
		if (popStyle == null) {
			this.oneStyle = document.createElement("style");
			this.oneStyle.id = rng;
			this.oneStyle.textContent = ` ${this.fInfo.extra.css} `;
		} else {
			this.cStyle();
		}
	}

	rng(length) {
		let result = "";
		const digits = "0123456789";
		for (let i = 0; i < length; i++) {
			result += digits.charAt(Math.floor(Math.random() * digits.length));
		}
		return "id" + result;
	}
}
