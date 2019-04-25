// EventListener hinzufügen
function makeDraggable(evt) {

	// var svg = document.getElementById('main_svg');
	var svg = evt.target;

	document.getElementById('toSvg').addEventListener('click', exportSVG);
	document.getElementById('toPng').addEventListener('click', downloadPng);

	// Register drag event to all images
	var images = $(".draggable");
	for (var i = 0; i < images.length; i++)
	{
		images[i].addEventListener('click', evaluateDrag);
		images[i].addEventListener('mousemove', drag);
		images[i].addEventListener('touchstart', evaluateDrag);
		images[i].addEventListener('touchmove', drag);
		images[i].addEventListener('touchend', endDrag);
		images[i].addEventListener('touchleave', endDrag);
		images[i].addEventListener('touchcancel', endDrag);
	}

	// Register Rotate event to all circles
	var handle = $(".handle");
	for (var i = 0; i < handle.length; i++)
	{
		handle[i].addEventListener('mousedown', startRotate);
		handle[i].addEventListener('mouseup', endRotate);
		handle[i].addEventListener('mouseleave', endRotate);
		handle[i].addEventListener('touchstart', startRotate);
		handle[i].addEventListener('touchend', endRotate);
		handle[i].addEventListener('touchleave', endRotate);
		handle[i].addEventListener('touchcancel', endRotate);
	}

	var selectedElement, offset, transform,
		bbox, minX, maxX, minY, maxY, confined, elToRotate;

	var boundaryX1 = 0;
	var boundaryX2 = document.getElementById('boxX').value;
	var boundaryY1 = 0;
	var boundaryY2 = document.getElementById('boxY').value;

	function getMousePosition(evt) {
		var CTM = svg.getScreenCTM();
		if (evt.touches) { evt = evt.touches[0]; }
		return {
			x: (evt.clientX - CTM.e) / CTM.a,
			y: (evt.clientY - CTM.f) / CTM.d
		};
	}

	function evaluateDrag(evt) {
		if (selectedElement) {
			selectedElement.classList.remove('dragged');
			selectedElement = false;
		}
		else {
			if (evt.target.classList.contains('draggable')) {
				selectedElement = evt.target.parentNode;
				svg.removeChild(selectedElement);
				svg.insertBefore(selectedElement, svg.lastChild);
				offset = getMousePosition(evt);
				selectedElement.classList.add('dragged');

				// Make sure the first transform on the element is a translate transform
				var transforms = selectedElement.transform.baseVal;

				if (transforms.length === 0 || transforms.getItem(0).type !== SVGTransform.SVG_TRANSFORM_TRANSLATE) {
					// Create an transform that translates by (0, 0)
					var translate = svg.createSVGTransform();
					translate.setTranslate(0, 0);
					selectedElement.transform.baseVal.insertItemBefore(translate, 0);
				}

				// Get initial translation
				transform = transforms.getItem(0);
				offset.x -= transform.matrix.e;
				offset.y -= transform.matrix.f;

				confined = evt.target.classList.contains('confine');
				if (confined) {
					bbox = selectedElement.getBBox();
					minX = boundaryX1 - bbox.x;
					maxX = boundaryX2 - bbox.x - bbox.width;
					minY = boundaryY1 - bbox.y;
					maxY = boundaryY2 - bbox.y - bbox.height;
				}
			}
			drag(evt);
		}
	}

	function startRotate(evt) {
		if (evt.target.classList.contains('rotate')) {
			offset = getMousePosition(evt);
			selectedElement = evt.target;
			var elForCenter = selectedElement.parentNode.childNodes.item(0);
			bbox = elForCenter.getBBox();
			var transforms = elForCenter.transform.baseVal;

			if (transforms.length === 0 || transforms.getItem(0).type !== SVGTransform.SVG_TRANSFORM_TRANSLATE) {
				// Create an transform that translates by (0, 0)
				var translate = svg.createSVGTransform();
				translate.setRotate(0, bbox.width / 2, bbox.height / 2);
				elForCenter.transform.baseVal.insertItemBefore(translate, 0);
			}

			transform = transforms.getItem(0);
			rotate();
		}
	}

	function drag(evt) {
		if (selectedElement) {
			evt.preventDefault();

			var coord = getMousePosition(evt);
			var dx = coord.x - offset.x;
			var dy = coord.y - offset.y;

			if (confined) {
				if (dx < minX) { dx = minX; }
				else if (dx > maxX) { dx = maxX; }
				if (dy < minY) { dy = minY; }
				else if (dy > maxY) { dy = maxY; }
			}

			transform.setTranslate(dx, dy);
		}
	}

	function endDrag(evt) {
		selectedElement = false;
	}

	function rotate() {
		if (selectedElement) {
			evt.preventDefault();
			if (selectedElement.classList.contains('left'))
			{
				transform.setRotate(5, (bbox.x + bbox.width / 2), (bbox.y + bbox.height / 2));
			}
			else
			{
				transform.setRotate(-5, (bbox.x + bbox.width / 2), (bbox.y + bbox.height / 2));
			}
		}
	}

	function endRotate(evt) {
		selectedElement = false;
	}

	function generateLink(fileName, data) {
		var link = document.createElement('a'); // Create a element.
		link.download = fileName; // Set value as the file name of download file.
		link.href = data; // Set value as the file content of download file.
		return link;
	}

	function exportSVG() {
		//var outer = outerHTML(svg);
		styles(svg);
		var newSvg = svg;
		var svgString;
		if (window.ActiveXObject) {
			svgString = newSvg.xml;
		}
		else {
			var oSerializer = new XMLSerializer();
			svgString = oSerializer.serializeToString(newSvg);
		}

		generateLink('planing.svg', 'data:image/svg+xml;utf8,' + svgString).click();
	}

	function styles(dom) {
		var used = "";
		var sheets = document.styleSheets;
		for (var i = 0; i < sheets.length; i++) {
			try {
				var rules = sheets[i].cssRules;
				for (var j = 0; j < rules.length; j++) {
					var rule = rules[j];
					if (typeof(rule.style) != "undefined") {
						var elems = dom.querySelectorAll(rule.selectorText);
						if (elems.length > 0) {
							used += rule.selectorText + " { " + rule.style.cssText + " }\n";
						}
					}
				}
			}
			catch (e) {
			}
		}

		var s = document.createElement('style');
		s.setAttribute('type', 'text/css');
		s.innerHTML = "<![CDATA[\n" + used + "\n]]>";

		var defs = document.createElement('defs');
		defs.appendChild(s);
		dom.insertBefore(defs, dom.firstChild);
	}

	function downloadPng() {
		styles(svg);
		downloadSvg('planing.png');
	}

	function triggerDownload (imgURI, fileName) {
		var evt = new MouseEvent("click", {
			view: window,
			bubbles: false,
			cancelable: true
		});
		var a = document.createElement("a");
		a.setAttribute("download", fileName);
		a.setAttribute("href", imgURI);
		a.setAttribute("target", '_blank');
		a.dispatchEvent(evt);
	}

	function downloadSvg(fileName) {
		var copy = svg.cloneNode(true);
		var canvas = document.createElement("canvas");
		var bbox = svg.getBBox();
		canvas.width = 800; // bbox.width;
		canvas.height = 800; //bbox.height;
		var ctx = canvas.getContext("2d");
		ctx.clearRect(0, 0, bbox.width, bbox.height);
		var data = (new XMLSerializer()).serializeToString(copy);
		var DOMURL = window.URL || window.webkitURL || window;
		var img = new Image();
		var svgBlob = new Blob([data], {type: "image/svg+xml;charset=utf-8"});
		var url = DOMURL.createObjectURL(svgBlob);
		img.onload = function () {
			ctx.drawImage(img, 0, 0);
			DOMURL.revokeObjectURL(url);
			if (typeof navigator !== "undefined" && navigator.msSaveOrOpenBlob)
			{
				var blob = canvas.msToBlob();
				navigator.msSaveOrOpenBlob(blob, fileName);
			}
			else {
				var imgURI = canvas
					.toDataURL("image/png")
					.replace("image/png", "image/octet-stream");
				triggerDownload(imgURI, fileName);
			}
			document.removeChild(canvas);
		};
		img.src = url;
	}
}
