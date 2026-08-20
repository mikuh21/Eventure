const MAX_PHOTO_BYTES = 5 * 1024 * 1024;

function initializeParticipantPhotoCropper() {
    const inputs = document.querySelectorAll('input[type="file"][name="photo"]');

    inputs.forEach((input) => {
        if (input.dataset.photoCropperInitialized === 'true') return;
        input.dataset.photoCropperInitialized = 'true';
        const status = document.querySelector(`[data-photo-status="${input.id}"]`);

        const setStatus = (message, isError = false) => {
            if (!status) return;
            status.textContent = message;
            status.style.color = isError ? '#b91c1c' : '#1B6CA8';
        };

        const openCropper = (file) => {
            const image = new Image();
            const reader = new FileReader();

            reader.onload = () => {
                image.onload = () => {
                    const overlay = document.createElement('div');
                    overlay.className = 'participant-photo-crop-overlay';
                    if (input.closest('#landingRegistrationModal')) {
                        overlay.classList.add('is-landing-photo-crop');
                    }
                    overlay.innerHTML = `
                        <div class="participant-photo-crop-dialog" role="dialog" aria-modal="true" aria-labelledby="participantPhotoCropTitle">
                            <h2 class="participant-photo-crop-title" id="participantPhotoCropTitle">Participant Photo</h2>
                            <canvas class="participant-photo-crop-canvas" width="640" height="640"></canvas>
                            <p class="participant-photo-crop-help">Drag to reposition or pinch to zoom.</p>
                            <div class="participant-photo-crop-actions">
                                <button type="button" class="participant-photo-crop-action" data-photo-crop-cancel>Cancel</button>
                                <button type="button" class="participant-photo-crop-action is-primary" data-photo-crop-confirm>Use Photo</button>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(overlay);

                    const canvas = overlay.querySelector('canvas');
                    const context = canvas.getContext('2d');
                    const canvasSize = canvas.width;
                    const scaleToCover = Math.max(canvasSize / image.width, canvasSize / image.height);
                    const cropState = {
                        scale: scaleToCover,
                        minScale: scaleToCover,
                        x: (canvasSize - image.width * scaleToCover) / 2,
                        y: (canvasSize - image.height * scaleToCover) / 2,
                        dragging: false,
                        lastX: 0,
                        lastY: 0,
                    };

                    const draw = () => {
                        context.clearRect(0, 0, canvasSize, canvasSize);
                        context.fillStyle = '#0A2342';
                        context.fillRect(0, 0, canvasSize, canvasSize);
                        context.drawImage(image, cropState.x, cropState.y, image.width * cropState.scale, image.height * cropState.scale);
                    };

                    const zoom = (factor, centerX = canvasSize / 2, centerY = canvasSize / 2) => {
                        const nextScale = Math.max(cropState.minScale, Math.min(cropState.scale * factor, cropState.minScale * 4));
                        const ratio = nextScale / cropState.scale;
                        cropState.x = centerX - (centerX - cropState.x) * ratio;
                        cropState.y = centerY - (centerY - cropState.y) * ratio;
                        cropState.scale = nextScale;
                        draw();
                    };

                    const pointFromEvent = (event) => {
                        const bounds = canvas.getBoundingClientRect();
                        return { x: (event.clientX - bounds.left) * (canvas.width / bounds.width), y: (event.clientY - bounds.top) * (canvas.height / bounds.height) };
                    };

                    const activePointers = new Map();
                    let pinchDistance = null;

                    const getPinchPoints = () => [...activePointers.values()];
                    const getPinchDistance = (points) => Math.hypot(points[1].x - points[0].x, points[1].y - points[0].y);
                    const getPinchCenter = (points) => ({
                        x: (points[0].x + points[1].x) / 2,
                        y: (points[0].y + points[1].y) / 2,
                    });

                    canvas.addEventListener('pointerdown', (event) => {
                        activePointers.set(event.pointerId, { x: event.clientX, y: event.clientY });
                        if (activePointers.size === 2) {
                            cropState.dragging = false;
                            pinchDistance = getPinchDistance(getPinchPoints());
                            event.stopImmediatePropagation();
                        }
                    });
                    canvas.addEventListener('pointermove', (event) => {
                        if (!activePointers.has(event.pointerId)) return;
                        activePointers.set(event.pointerId, { x: event.clientX, y: event.clientY });
                        if (activePointers.size !== 2) return;
                        const points = getPinchPoints();
                        const nextDistance = getPinchDistance(points);
                        const center = pointFromEvent({
                            clientX: getPinchCenter(points).x,
                            clientY: getPinchCenter(points).y,
                        });
                        if (pinchDistance && nextDistance > 0) {
                            zoom(nextDistance / pinchDistance, center.x, center.y);
                        }
                        pinchDistance = nextDistance;
                        event.preventDefault();
                        event.stopImmediatePropagation();
                    }, { passive: false });
                    canvas.addEventListener('pointerup', (event) => {
                        activePointers.delete(event.pointerId);
                        if (activePointers.size < 2) pinchDistance = null;
                    });
                    canvas.addEventListener('pointercancel', (event) => {
                        activePointers.delete(event.pointerId);
                        if (activePointers.size < 2) pinchDistance = null;
                    });

                    canvas.addEventListener('pointerdown', (event) => {
                        cropState.dragging = true;
                        cropState.lastX = event.clientX;
                        cropState.lastY = event.clientY;
                        canvas.setPointerCapture(event.pointerId);
                    });
                    canvas.addEventListener('pointermove', (event) => {
                        if (!cropState.dragging) return;
                        const bounds = canvas.getBoundingClientRect();
                        cropState.x += (event.clientX - cropState.lastX) * (canvas.width / bounds.width);
                        cropState.y += (event.clientY - cropState.lastY) * (canvas.height / bounds.height);
                        cropState.lastX = event.clientX;
                        cropState.lastY = event.clientY;
                        draw();
                    });
                    canvas.addEventListener('pointerup', () => { cropState.dragging = false; });
                    canvas.addEventListener('pointercancel', () => { cropState.dragging = false; });
                    canvas.addEventListener('wheel', (event) => {
                        event.preventDefault();
                        const point = pointFromEvent(event);
                        zoom(event.deltaY < 0 ? 1.08 : 0.92, point.x, point.y);
                    }, { passive: false });

                    const close = () => overlay.remove();
                    overlay.querySelector('[data-photo-crop-cancel]').addEventListener('click', close);
                    overlay.addEventListener('click', (event) => { if (event.target === overlay) close(); });
                    overlay.querySelector('[data-photo-crop-confirm]').addEventListener('click', () => {
                        canvas.toBlob((blob) => {
                            if (!blob) {
                                setStatus('The photo could not be processed.', true);
                                close();
                                return;
                            }
                            const transfer = new DataTransfer();
                            transfer.items.add(new File([blob], 'participant-photo.jpg', { type: 'image/jpeg' }));
                            input.files = transfer.files;
                            setStatus('Photo ready.');
                            close();
                        }, 'image/jpeg', 0.9);
                    });
                    draw();
                };
                image.onerror = () => setStatus('The selected file is not a valid image.', true);
                image.src = reader.result;
            };
            reader.onerror = () => setStatus('The selected photo could not be read.', true);
            reader.readAsDataURL(file);
        };

        input.addEventListener('change', () => {
            const [file] = input.files;
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                input.value = '';
                setStatus('Please choose a supported image file.', true);
                return;
            }
            if (file.size > MAX_PHOTO_BYTES) {
                input.value = '';
                setStatus('Photo must be 5 MB or smaller.', true);
                return;
            }
            input.value = '';
            openCropper(file);
        });

        document.querySelectorAll(`[data-photo-input="${input.id}"]`).forEach((button) => {
            button.addEventListener('click', () => {
                if (button.dataset.photoSource === 'camera') input.setAttribute('capture', 'environment');
                else input.removeAttribute('capture');
                input.click();
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', initializeParticipantPhotoCropper);
