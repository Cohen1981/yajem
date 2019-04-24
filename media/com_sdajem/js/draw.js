// EventListener hinzufügen
function makeDraggable(evt) {
	var svg = evt.target;

	// Register drag event to all images
	var images = $(".draggable");
	for (var i = 0; i < images.length; i++)
	{
		images[i].addEventListener('click', evaluateDrag);
		images[i].addEventListener('mousemove', drag);
		//images[i].addEventListener('touchstart', startDrag);
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
				translate.setRotate(0, bbox.width/2, bbox.height/2);
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
}
