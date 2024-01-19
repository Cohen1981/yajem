/**
 * @copyright: abahlo@hotmail.de
 * @licence Gnu GPL
 * Load the magic
 */
;(function () {
    'use strict'

    let el, offset, transform,
        bbox, minX, maxX, minY, maxY, confined, elForCenter;

    const svg = document.getElementById('main_svg');
    const boundaryX1 = 0;
    const boundaryX2 = document.getElementById('boxX').value;
    const boundaryY1 = 0;
    const boundaryY2 = document.getElementById('boxY').value;

    const dragBoxes = $('.draggable');
    const rotators = $('.rotator');

    let dragging = false;
    let rotating = false;

    dragBoxes.each(function (){
        this.addEventListener('mousedown', startDrag);
        this.addEventListener('touchstart', startDrag);
    });

    rotators.each(function (){
        this.addEventListener('mousedown', startRotate);
        this.addEventListener('touchstart', startRotate);
    });

    document.addEventListener('mouseup', stopAll);
    document.addEventListener('touchend', stopAll);
    document.addEventListener('mousemove', moving);
    document.addEventListener('touchmove', moving);

    function getMousePosition(evt) {
        let CTM = svg.getScreenCTM();
        if (evt.touches) { evt = evt.touches[0]; }

        return {
            x: (evt.clientX - CTM.e) / CTM.a,
            y: (evt.clientY - CTM.f) / CTM.d
        };
    }

    function startDrag(evt) {
        dragging = true;
        el = evt.target.parentElement.parentElement;

        offset = getMousePosition(evt);

        offset.x -= el.x.baseVal.value;
        offset.y -= el.y.baseVal.value;

        bbox = {
            x:el.x.baseVal.value,
            y:el.y.baseVal.value,
            height:el.height.baseVal.value,
            width:el.width.baseVal.value
        }

        confined = el.classList.contains('confine');
        if (confined) {
            minX = boundaryX1;
            maxX = boundaryX2 - bbox.width;
            minY = boundaryY1;
            maxY = boundaryY2 - bbox.height;
        }
    }

    function startRotate(evt) {
        rotating = true;
        el = evt.target.parentElement;

        elForCenter = el.childNodes.item(0);
        bbox = elForCenter.getBBox();
        let transforms = el.transform.baseVal;

        if (transforms.length === 0 || transforms.getItem(0).type !== SVGTransform.SVG_TRANSFORM_ROTATE) {
            // Create an transform that translates by (0, 0)
            let translate = svg.createSVGTransform();
            translate.setRotate(0, bbox.width / 2, bbox.height / 2);
            el.transform.baseVal.insertItemBefore(translate, 0);
        }

        transform = transforms.getItem(0);
    }

    function moving(evt) {
        if (dragging) {
            let coord = getMousePosition(evt);

            let dx = coord.x - offset.x;
            let dy = coord.y - offset.y;

            if (confined) {
                if (dx < minX) {
                    dx = minX;
                }
                else if (dx > maxX) {
                    dx = maxX;
                }

                if (dy < minY) { dy = minY; }
                else if (dy > maxY) { dy = maxY; }
            }

            el.setAttributeNS(null, 'x', dx);
            el.setAttributeNS(null, 'y', dy);
        }
        if (rotating) {

            let elForM = el.parentElement;
            let bboxFM = {
                x:elForM.x.baseVal.value,
                y:elForM.y.baseVal.value,
                height:elForM.height.baseVal.value,
                width:elForM.width.baseVal.value
            }

            const rotateCenterX = bboxFM.x + bbox.width/2;
            const rotateCenterY = bboxFM.y + bbox.height/2;

            offset = getMousePosition(evt);

            let deltaX = offset.x - rotateCenterX;
            let deltaY = offset.y - rotateCenterY;

            console.log(deltaX + ' ' + deltaY);

            let angle = Math.atan2(deltaY, deltaX) * (180 / Math.PI) - 90;
            console.log(angle);
            transform.setRotate(angle, bbox.width/2, bbox.height/2);
        }
    }

    function stopAll() {
        dragging = false;
        rotating = false;
        el = false;
    }

})();