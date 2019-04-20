// EventListener hinzufügen
function makeDraggable(evt) {
	var svg = evt.target;

	var images = document.getElementsByName('fitting');
	images.forEach(
		function (img, index) {
			img.addEventListener('mousedown', startDrag);
			img.addEventListener('mousemove', drag);
			img.addEventListener('mouseup', endDrag);
			img.addEventListener('mouseleave', endDrag);
			img.addEventListener('touchstart', startDrag);
			img.addEventListener('touchmove', drag);
			img.addEventListener('touchend', endDrag);
			img.addEventListener('touchleave', endDrag);
			img.addEventListener('touchcancel', endDrag);
		}
	);
	var handle = document.getElementsByName('handle');
	handle.forEach(
		function (handle, index) {
			handle.addEventListener('mousedown', startRotate);
			handle.addEventListener('mousemove', rotate);
			handle.addEventListener('mouseup', endRotate);
			handle.addEventListener('mouseleave', endRotate);
			handle.addEventListener('touchstart', startRotate);
			handle.addEventListener('touchmove', rotate);
			handle.addEventListener('touchend', endRotate);
			handle.addEventListener('touchleave', endRotate);
			handle.addEventListener('touchcancel', endRotate);
		}
	);

	var selectedElement, offset, transform,
		bbox, minX, maxX, minY, maxY, confined, elToRotate;

	var boundaryX1 = 0;
	var boundaryX2 = 50;
	var boundaryY1 = 0;
	var boundaryY2 = 50;

	function getMousePosition(evt) {
		var CTM = svg.getScreenCTM();
		if (evt.touches) { evt = evt.touches[0]; }
		return {
			x: (evt.clientX - CTM.e) / CTM.a,
			y: (evt.clientY - CTM.f) / CTM.d
		};
	}

	function startDrag(evt) {
		if (evt.target.classList.contains('draggable')) {
			selectedElement = evt.target.parentNode;
			offset = getMousePosition(evt);

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
				translate.setRotate(0, bbox.width/2, bbox.height/2);
				elForCenter.transform.baseVal.insertItemBefore(translate, 0);
			}

			transform = transforms.getItem(0);
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

	function rotate(evt) {
		if (selectedElement) {
			evt.preventDefault();
			transform.setRotate(10, (bbox.x + bbox.width / 2), (bbox.y + bbox.height / 2));
		}
	}

	function endDrag(evt) {
		selectedElement = false;
	}

	function endRotate(evt) {
		selectedElement = false;
	}
}
