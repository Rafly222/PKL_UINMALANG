/**
 * signature.js
 * Modul Tanda Tangan Digital menggunakan POINTER EVENTS API.
 * Menjamin coretan berjalan 100% lancar di Touchscreen PC Kerja, Laptop, HP, maupun Tablet.
 */

class SignaturePad {
    constructor(canvasId, clearButtonId, inputHiddenId) {
        this.canvas = document.getElementById(canvasId);
        this.clearBtn = document.getElementById(clearButtonId);
        this.inputHidden = document.getElementById(inputHiddenId);
        
        if (!this.canvas) return;

        this.ctx = this.canvas.getContext('2d');
        this.isDrawing = false;
        
        this.init();
    }

    init() {
        // Mengatur ukuran resolusi internal canvas agar tanda tangan tidak blur/pecah
        this.resizeCanvas();
        window.addEventListener('resize', () => this.resizeCanvas());

        // POINTER EVENTS - Solusi jitu menyatukan Touch, Mouse, dan Stylus Pen
        this.canvas.addEventListener('pointerdown', (e) => this.startDrawing(e));
        this.canvas.addEventListener('pointermove', (e) => this.draw(e));
        this.canvas.addEventListener('pointerup', (e) => this.stopDrawing(e));
        this.canvas.addEventListener('pointerleave', (e) => this.stopDrawing(e));

        // Event Bersihkan Canvas
        if (this.clearBtn) {
            this.clearBtn.addEventListener('click', () => this.clear());
        }
    }

    resizeCanvas() {
        const rect = this.canvas.getBoundingClientRect();
        // Gunakan device pixel ratio untuk rendering tajam di layar resolusi tinggi
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        this.canvas.width = rect.width * ratio;
        this.canvas.height = rect.height * ratio;
        this.ctx.scale(ratio, ratio);
        
        // Atur kembali parameter brush setelah canvas di-resize
        this.ctx.lineWidth = 2.5;
        this.ctx.lineCap = 'round';
        this.ctx.lineJoin = 'round';
        this.ctx.strokeStyle = '#1e3a8a'; // Warna biru tua formal (Diskominfo)
    }

    getCoordinates(e) {
        const rect = this.canvas.getBoundingClientRect();
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
    }

    startDrawing(e) {
        this.isDrawing = true;
        const coords = this.getCoordinates(e);
        this.ctx.beginPath();
        this.ctx.moveTo(coords.x, coords.y);
        
        // Amankan pointer focus ke canvas (agar coretan tidak terputus saat diseret cepat)
        this.canvas.setPointerCapture(e.pointerId);
    }

    draw(e) {
        if (!this.isDrawing) return;
        
        const coords = this.getCoordinates(e);
        this.ctx.lineTo(coords.x, coords.y);
        this.ctx.stroke();
    }

    stopDrawing(e) {
        if (!this.isDrawing) return;
        this.isDrawing = false;
        
        // Lepas pointer capture
        this.canvas.releasePointerCapture(e.pointerId);
        
        // Konversikan coretan menjadi base64 string untuk dikirim lewat input Laravel
        this.saveSignature();
    }

    clear() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        if (this.inputHidden) {
            this.inputHidden.value = '';
        }
        this.resizeCanvas();
    }

    saveSignature() {
        // Cek apakah canvas kosong sebelum menyimpan
        if (this.isEmpty()) {
            if (this.inputHidden) this.inputHidden.value = '';
            return;
        }
        
        const dataUrl = this.canvas.toDataURL('image/png');
        if (this.inputHidden) {
            this.inputHidden.value = dataUrl;
        }
    }

    isEmpty() {
        // Fungsi pembantu sederhana untuk mengecek apakah canvas memiliki coretan
        const buffer = new Uint32Array(
            this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height).data.buffer
        );
        return !buffer.some(color => color !== 0);
    }
}

// Inisialisasi pad saat DOM siap
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('signature-pad')) {
        window.signatureEngine = new SignaturePad('signature-pad', 'clear-signature', 'tanda_tangan_input');
    }
});