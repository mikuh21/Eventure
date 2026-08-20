/**
 * Photo Cropper - Square-only image cropping for participant photos
 * Enforces 1:1 aspect ratio (square) - cannot be resized to rectangle
 */

class PhotoCropper {
    constructor(prefix = 'photo') {
        this.prefix = prefix;
        this.currentImage = null;
        this.cropData = null;
    }

    getId(baseName) {
        if (this.prefix === 'photo') {
            return baseName;
        }
        return this.prefix + baseName.charAt(0).toUpperCase() + baseName.slice(1);
    }

    init() {
        this.setupEventListeners();
        this.createCropModal();
    }

    setupEventListeners() {
        const chooseBtn = document.getElementById(this.getId('choosePhotoBtn'));
        if (chooseBtn) {
            chooseBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.triggerFileInput('upload');
            });
        }

        const takeBtn = document.getElementById(this.getId('takePhotoBtn'));
        if (takeBtn) {
            takeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.triggerFileInput('camera');
            });
        }

        const removeBtn = document.getElementById(this.getId('photoRemove'));
        if (removeBtn) {
            removeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.removePhoto();
            });
        }
    }

    triggerFileInput(source) {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        
        if (source === 'camera') {
            input.capture = 'environment';
        }

        input.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (file) {
                this.handleFileSelection(file);
            }
        });

        input.click();
    }

    handleFileSelection(file) {
        if (!file.type.startsWith('image/')) {
            this.showError('Please select a valid image file');
            return;
        }

        const maxFileSize = 5 * 1024 * 1024;
        if (file.size > maxFileSize) {
            this.showError('File size exceeds 5MB limit');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                this.currentImage = img;
                this.showCropModal(img);
            };
            img.onerror = () => {
                this.showError('Failed to load image');
            };
            img.src = e.target?.result;
        };
        reader.readAsDataURL(file);
    }

    createCropModal() {
        const modal = document.createElement('div');
        modal.id = 'photoCropModal';
        modal.style.cssText = `
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 10000;
            justify-content: center;
            align-items: center;
        `;

        modal.innerHTML = `
            <div style="background: white; border-radius: 12px; padding: 20px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);">
                <h3 style="margin: 0 0 15px 0; font-size: 18px; font-weight: 600; color: #1f2937;">Crop Photo (Square)</h3>
                <p style="margin: 0 0 15px 0; font-size: 14px; color: #6b7280;">Drag to position, use zoom to adjust size. Image will be saved as a square.</p>
                
                <div style="position: relative; background: #f3f4f6; border-radius: 8px; overflow: hidden; margin-bottom: 15px;">
                    <canvas id="photoCropCanvas" style="display: block; max-width: 100%; cursor: grab;"></canvas>
                </div>

                <div id="zoomControls" style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 14px; color: #374151; margin-bottom: 8px;">
                        Zoom: <span id="zoomValue">100</span>%
                    </label>
                    <input type="range" id="zoomSlider" min="50" max="200" value="100" style="width: 100%;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <button id="photoCropCancel" style="padding: 10px 16px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #374151; font-weight: 600; cursor: pointer; font-size: 14px;">Cancel</button>
                    <button id="photoCropUse" style="padding: 10px 16px; background: #1B6CA8; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">Use Photo</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        document.getElementById('photoCropCancel').addEventListener('click', () => {
            this.closeCropModal();
        });

        document.getElementById('photoCropUse').addEventListener('click', () => {
            this.saveCroppedPhoto();
        });

        const zoomSlider = document.getElementById('zoomSlider');
        zoomSlider.addEventListener('input', (e) => {
            document.getElementById('zoomValue').textContent = e.target.value;
            this.drawCropPreview();
        });

        const canvas = document.getElementById('photoCropCanvas');
        let isDragging = false;
        let dragStartX = 0;
        let dragStartY = 0;

        canvas.addEventListener('mousedown', (e) => {
            isDragging = true;
            dragStartX = e.clientX;
            dragStartY = e.clientY;
            canvas.style.cursor = 'grabbing';
        });

        canvas.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            
            const deltaX = e.clientX - dragStartX;
            const deltaY = e.clientY - dragStartY;

            if (!this.cropData.offsetX) this.cropData.offsetX = 0;
            if (!this.cropData.offsetY) this.cropData.offsetY = 0;

            this.cropData.offsetX += deltaX;
            this.cropData.offsetY += deltaY;

            dragStartX = e.clientX;
            dragStartY = e.clientY;

            this.drawCropPreview();
        });

        canvas.addEventListener('mouseup', () => {
            isDragging = false;
            canvas.style.cursor = 'grab';
        });

        canvas.addEventListener('mouseleave', () => {
            isDragging = false;
            canvas.style.cursor = 'grab';
        });

        canvas.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1) {
                isDragging = true;
                dragStartX = e.touches[0].clientX;
                dragStartY = e.touches[0].clientY;
            }
        });

        canvas.addEventListener('touchmove', (e) => {
            if (!isDragging || e.touches.length !== 1) return;
            e.preventDefault();

            const deltaX = e.touches[0].clientX - dragStartX;
            const deltaY = e.touches[0].clientY - dragStartY;

            if (!this.cropData.offsetX) this.cropData.offsetX = 0;
            if (!this.cropData.offsetY) this.cropData.offsetY = 0;

            this.cropData.offsetX += deltaX;
            this.cropData.offsetY += deltaY;

            dragStartX = e.touches[0].clientX;
            dragStartY = e.touches[0].clientY;

            this.drawCropPreview();
        });

        canvas.addEventListener('touchend', () => {
            isDragging = false;
        });
    }

    showCropModal(img) {
        const modal = document.getElementById('photoCropModal');
        const canvas = document.getElementById('photoCropCanvas');
        const rect = canvas.parentElement.getBoundingClientRect();
        
        const size = Math.min(rect.width, 400);
        canvas.width = size;
        canvas.height = size;

        this.cropData = {
            scale: 1,
            offsetX: 0,
            offsetY: 0
        };

        this.drawCropPreview();
        modal.style.display = 'flex';
    }

    drawCropPreview() {
        const canvas = document.getElementById('photoCropCanvas');
        const ctx = canvas.getContext('2d');
        const img = this.currentImage;
        const zoom = parseInt(document.getElementById('zoomSlider').value) / 100;

        ctx.fillStyle = '#f3f4f6';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        const scale = (Math.max(canvas.width, canvas.height) / Math.max(img.width, img.height)) * zoom;
        const x = (canvas.width - img.width * scale) / 2 + (this.cropData.offsetX || 0);
        const y = (canvas.height - img.height * scale) / 2 + (this.cropData.offsetY || 0);

        ctx.drawImage(img, x, y, img.width * scale, img.height * scale);

        ctx.strokeStyle = '#1B6CA8';
        ctx.lineWidth = 2;
        ctx.strokeRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = 'rgba(0, 0, 0, 0.3)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.clearRect(1, 1, canvas.width - 2, canvas.height - 2);
    }

    saveCroppedPhoto() {
        const canvas = document.getElementById('photoCropCanvas');
        const croppedCanvas = document.createElement('canvas');
        croppedCanvas.width = canvas.width;
        croppedCanvas.height = canvas.height;

        const ctx = croppedCanvas.getContext('2d');
        const img = this.currentImage;
        const zoom = parseInt(document.getElementById('zoomSlider').value) / 100;

        const scale = (Math.max(canvas.width, canvas.height) / Math.max(img.width, img.height)) * zoom;
        const x = (canvas.width - img.width * scale) / 2 + (this.cropData.offsetX || 0);
        const y = (canvas.height - img.height * scale) / 2 + (this.cropData.offsetY || 0);

        ctx.drawImage(img, x, y, img.width * scale, img.height * scale);

        croppedCanvas.toBlob((blob) => {
            if (blob) {
                this.displayCroppedPhoto(blob);
                this.closeCropModal();
            }
        }, 'image/jpeg', 0.95);
    }

    displayCroppedPhoto(blob) {
        const url = URL.createObjectURL(blob);
        
        const imgId = this.getId('photoImg');
        const previewId = this.getId('photoPreview');
        const fileInputId = this.getId('photoFileInput');

        const preview = document.getElementById(previewId);
        const img = document.getElementById(imgId);
        const fileInput = document.getElementById(fileInputId);

        if (img) img.src = url;
        if (preview) preview.style.display = 'block';

        const file = new File([blob], 'photo.jpg', { type: 'image/jpeg' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);

        if (fileInput) {
            fileInput.files = dataTransfer.files;
        }
    }

    removePhoto() {
        const previewId = this.getId('photoPreview');
        const fileInputId = this.getId('photoFileInput');

        const preview = document.getElementById(previewId);
        const fileInput = document.getElementById(fileInputId);

        if (preview) preview.style.display = 'none';
        if (fileInput) fileInput.value = '';
    }

    closeCropModal() {
        const modal = document.getElementById('photoCropModal');
        modal.style.display = 'none';
    }

    showError(message) {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ef4444;
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            z-index: 10001;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        `;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 3000);
    }
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        const adminCropper = new PhotoCropper('photo');
        adminCropper.init();

        const landingCropper = new PhotoCropper('landingPhoto');
        landingCropper.init();
    });
} else {
    const adminCropper = new PhotoCropper('photo');
    adminCropper.init();

    const landingCropper = new PhotoCropper('landingPhoto');
    landingCropper.init();
}
