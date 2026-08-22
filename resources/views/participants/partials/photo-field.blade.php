@php
    $photoInputId = $inputId ?? 'participantPhoto';
    $photoFieldId = $fieldId ?? $photoInputId.'Field';
    $photoFieldClass = $fieldClass ?? '';
@endphp

<div id="{{ $photoFieldId }}" class="participant-photo-field {{ $photoFieldClass }}">
    <label for="{{ $photoInputId }}" class="participant-photo-label">Participant Photo</label>
    <input id="{{ $photoInputId }}" name="photo" type="file" accept="image/jpeg,image/png,image/webp" hidden>
    <div class="participant-photo-actions">
        <button type="button" class="participant-photo-button" data-photo-source="library" data-photo-input="{{ $photoInputId }}">Choose from Photos</button>
        <button type="button" class="participant-photo-button" data-photo-source="camera" data-photo-input="{{ $photoInputId }}">Take Photo</button>
    </div>
    <p class="participant-photo-required-error" data-photo-required-error="{{ $photoInputId }}" role="alert" hidden>Your photo is required.</p>
    <div class="participant-photo-preview-wrapper" hidden>
        <img class="participant-photo-preview" alt="Participant photo preview" aria-live="polite">
    </div>
    <p class="participant-photo-status" data-photo-status="{{ $photoInputId }}" aria-live="polite"></p>
</div>

<style>
    .participant-photo-field {
        font-family: 'Sora', sans-serif;
    }

    .participant-photo-label {
        display: block;
        margin-bottom: 7px;
        color: #0A2342;
        font-size: 13px;
        font-weight: 600;
    }

    .participant-photo-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .participant-photo-button,
    .participant-photo-crop-action {
        border: 1px solid #BFDFFF;
        border-radius: 8px;
        padding: 9px 12px;
        background: #ffffff;
        color: #1B6CA8;
        cursor: pointer;
        font-family: 'Sora', sans-serif;
        font-size: 12px;
        font-weight: 600;
    }

    .participant-photo-button:hover,
    .participant-photo-crop-action:hover {
        background: #E8F4FD;
    }

    .participant-photo-preview-wrapper {
        width: 80px;
        height: 80px;
        margin-top: 8px;
        border: 1px solid #BFDFFF;
        border-radius: 8px;
        background: #F3F9FF;
        overflow: hidden;
        box-sizing: border-box;
    }

    .participant-photo-preview {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        background: #0A2342;
    }

    .participant-photo-status {
        min-height: 18px;
        margin: 6px 0 0;
        color: #1B6CA8;
        font-size: 11px;
    }

    .participant-photo-required-error {
        margin: 6px 0 0;
        color: #b91c1c;
        font-family: 'Sora', sans-serif;
        font-size: 12px;
    }

    .participant-confirmation-modal {
        position: fixed;
        inset: 0;
        z-index: 11000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(10, 35, 66, 0.55);
        font-family: 'Sora', sans-serif;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 180ms ease, visibility 0s linear 180ms;
    }

    .participant-confirmation-modal.is-visible {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transition: opacity 180ms ease;
    }

    .participant-confirmation-panel {
        width: min(430px, 100%);
        max-height: calc(100vh - 40px);
        overflow-y: auto;
        padding: 24px;
        border: 1px solid #BFDFFF;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 8px 40px rgba(10, 35, 66, 0.18);
        box-sizing: border-box;
        transform: translateY(10px) scale(0.98);
        opacity: 0;
        transition: transform 220ms ease, opacity 220ms ease;
    }

    .participant-confirmation-modal.is-visible .participant-confirmation-panel {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .participant-confirmation-title {
        margin: 0;
        color: #0A2342;
        font-size: 18px;
        font-weight: 700;
    }

    .participant-confirmation-copy {
        margin: 8px 0 18px;
        color: #1B6CA8;
        font-size: 12px;
        line-height: 1.5;
    }

    .participant-confirmation-details {
        display: grid;
        gap: 12px;
        margin: 0;
    }

    .participant-confirmation-details div {
        min-width: 0;
    }

    .participant-confirmation-details dt {
        margin-bottom: 3px;
        color: #5BA4CF;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .participant-confirmation-details dd {
        margin: 0;
        color: #0A2342;
        font-size: 13px;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .participant-confirmation-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 22px;
    }

    .participant-confirmation-actions button {
        min-height: 38px;
        padding: 8px 14px;
        border: 1px solid #BFDFFF;
        border-radius: 8px;
        font-family: 'Sora', sans-serif;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .participant-confirmation-cancel {
        background: #ffffff;
        color: #1B6CA8;
    }

    .participant-confirmation-submit {
        border-color: #1B6CA8 !important;
        background: #1B6CA8;
        color: #ffffff;
    }

    @media (max-width: 480px) {
        .participant-confirmation-modal {
            padding: 14px;
        }

        .participant-confirmation-panel {
            padding: 20px;
        }

        .participant-confirmation-actions {
            justify-content: stretch;
        }

        .participant-confirmation-actions button {
            flex: 1;
        }
    }

    .participant-photo-crop-overlay {
        position: fixed;
        inset: 0;
        z-index: 1200;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(10, 35, 66, 0.62);
        font-family: 'Sora', sans-serif;
    }

    .participant-photo-crop-overlay.is-landing-photo-crop {
        z-index: 10000;
    }

    .participant-photo-crop-dialog {
        width: min(420px, 100%);
        padding: 20px;
        border: 1px solid #BFDFFF;
        border-radius: 12px;
        background: #E8F4FD;
    }

    .participant-photo-crop-title {
        margin: 0 0 12px;
        color: #0A2342;
        font-size: 16px;
        font-weight: 700;
    }

    .participant-photo-crop-canvas {
        display: block;
        width: min(320px, 100%);
        aspect-ratio: 1 / 1;
        margin: 0 auto;
        border: 2px solid #ffffff;
        background: #0A2342;
        cursor: grab;
        touch-action: none;
    }

    .participant-photo-crop-canvas:active {
        cursor: grabbing;
    }

    .participant-photo-crop-help {
        margin: 10px 0 0;
        color: #1B6CA8;
        font-size: 11px;
        text-align: center;
    }

    .participant-photo-crop-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 14px;
    }

    .participant-photo-crop-action.is-primary {
        border-color: #1B6CA8;
        background: #1B6CA8;
        color: #ffffff;
    }
</style>

@once
    <script src="{{ asset('participant-photo-cropper.js') }}"></script>
@endonce
