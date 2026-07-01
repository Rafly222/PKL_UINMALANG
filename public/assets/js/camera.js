/**
 * camera.js
 * Modul pengolah integrasi Kamera/Webcam berbasis HTML5.
 * Dilengkapi dengan fallback pengunggahan file jika perangkat komputer dinas tidak memiliki webcam.
 */

class CameraEngine {
    constructor(videoElementId, captureButtonId, canvasElementId, outputInputId, previewImageId, fallbackInputId) {
        this.video = document.getElementById(videoElementId);
        this.captureBtn = document.getElementById(captureButtonId);
        this.canvas = document.getElementById(canvasElementId);
        this.outputInput = document.getElementById(outputInputId);
        this.previewImg = document.getElementById(previewImageId);
        this.fallbackInput = document.getElementById(fallbackInputId);
        
        this.stream = null;
        
        if (this.video) {
            this.startWebcam();
        }
        this.initEvents();
    }

    async startWebcam() {
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: { width: 640, height: 480, facingMode: 'user' },
                audio: false
            });
            this.video.srcObject = this.stream;
        } catch (error) {
            console.warn("Webcam tidak terdeteksi atau diblokir. Mengaktifkan fallback unggah foto.", error);
            this.activateFallback();
        }
    }

    initEvents() {
        if (this.captureBtn) {
            this.captureBtn.addEventListener('click', () => this.capture());
        }

        // Event listener jika user menggunakan input upload berkas manual (Fallback)
        if (this.fallbackInput) {
            this.fallbackInput.addEventListener('change', (e) => this.handleFileSelect(e));
        }
    }

    capture() {
        if (!this.stream) {
            alert("Kamera tidak aktif. Silakan gunakan tombol unggah foto cadangan di bawah.");
            return;
        }

        const ctx = this.canvas.getContext('2d');
        this.canvas.width = this.video.videoWidth;
        this.canvas.height = this.video.videoHeight;
        
        // Gambar frame video saat ini ke canvas
        ctx.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);
        
        // Berikan efek kedip kilatan kamera
        const container = this.video.parentElement;
        container.classList.add('camera-flash');
        setTimeout(() => container.classList.remove('camera-flash'), 200);

        // Ambil data base64
        const dataUrl = this.canvas.toDataURL('image/jpeg', 0.85);
        this.savePhoto(dataUrl);
    }

    handleFileSelect(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (event) => {
            const dataUrl = event.target.result;
            this.savePhoto(dataUrl);
        };
        reader.readAsDataURL(file);
    }

    savePhoto(dataUrl) {
        // Tampilkan gambar pratinjau hasil jepretan
        if (this.previewImg) {
            this.previewImg.src = dataUrl;
            this.previewImg.classList.remove('hidden');
        }
        
        // Simpan ke input hidden agar terkirim ke Laravel Controller
        if (this.outputInput) {
            this.outputInput.value = dataUrl;
        }
        
        // Sembunyikan elemen live video agar hemat memori setelah berhasil capture
        if (this.video) {
            this.video.classList.add('opacity-40');
        }
    }

    activateFallback() {
        const videoWrapper = document.getElementById('video-wrapper');
        const fallbackWrapper = document.getElementById('camera-fallback-wrapper');
        
        if (videoWrapper) videoWrapper.classList.add('hidden');
        if (this.captureBtn) this.captureBtn.classList.add('hidden');
        if (fallbackWrapper) fallbackWrapper.classList.remove('hidden');
    }

    stopWebcam() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
        }
    }
}

// Inisialisasi kamera saat DOM siap
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('webcam-preview')) {
        window.cameraEngine = new CameraEngine(
            'webcam-preview',
            'capture-photo-btn',
            'camera-canvas',
            'foto_capture_input',
            'photo-preview-img',
            'photo-upload-fallback'
        );
    }
});