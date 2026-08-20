const MAX_PHOTO_BYTES = 5 * 1024 * 1024;

function initializeParticipantPhotoCropper() {
    const inputs = document.querySelectorAll('input[type="file"][name="photo"]');

    inputs.forEach((input) => {
        const status = document.querySelector(`[data-photo-status="${input.id}"]`);
        let cropState = null;

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
                    overlay.innerHTML = `
                        <div class="participant-photo-crop-dialog" role="dialog" aria-modal="true" aria-labelledby="participantPhotoCropTitle">
                            <h2 class="participant-photo-crop-title" id="participantPhotoCropTitle">Crop Participant Photo</h2>
                            <canvas class="participant-photo-crop-canvas" width="640" height="640"></canvas>
                            <p class="participant-photo-crop-help">Drag to reposition and use the mouse wheel or pinch to zoom. The crop is always square.</p>
                            <div class="participant-photo-crop-actions">
                                <button type="button" class="participant-photo-crop-action" data-photo-crop-cancel>Cancel</button>
                                <button type="button" class="participant-photo-crop-action is-primary" data-photo-crop-confirm>Crop / Use Photo</button>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(overlay);

                    const canvas = overlay.querySelector('canvas');
                    const context = canvas.getContext('2d');
                    const canvasSize = canvas.width;
                    const scaleToCover = Math.max(canvasSize / image.width, canvasSize / image.height);
                    cropState = {
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
                        context.drawImage(
                            image,
                            cropState.x,
                            cropState.y,
                            image.width * cropState.scale,
                            image.height * cropState.scale,
                        );
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
                        return {
                            x: (event.clientX - bounds.left) * (canvas.width / bounds.width),
                            y: (event.clientY - bounds.top) * (canvas.height / bounds.height),
                        };
                    };

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

                    const close = () => {
                        cropState = null;
                        overlay.remove();
                    };

                    overlay.querySelector('[data-photo-crop-cancel]').addEventListener('click', close);
                    overlay.addEventListener('click', (event) => {
                        if (event.target === overlay) close();
                    });
                    overlay.querySelector('[data-photo-crop-confirm]').addEventListener('click', () => {
                        canvas.toBlob((blob) => {
                            if (!blob) {
                                setStatus('The photo could not be processed.', true);
                                close();
                                return;
                            }
                            const croppedFile = new File([blob], 'participant-photo.jpg', { type: 'image/jpeg' });
                            const transfer = new DataTransfer();
                            transfer.items.add(croppedFile);
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
                if (button.dataset.photoSource === 'camera') {
                    input.setAttribute('capture', 'environment');
                } else {
                    input.removeAttribute('capture');
                }
                input.click();
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', initializeParticipantPhotoCropper);
