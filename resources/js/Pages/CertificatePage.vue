<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { jsPDF } from 'jspdf';
import html2canvas from 'html2canvas-pro';

// --- Props ---
const props = defineProps({
    certData: { type: Object, required: true },
    queryParams: { type: Object, required: false, default: () => ({}) },
});

const isDownloading = ref(false);

// Compile and download PDF
const downloadDirectly = async () => {
    if (isDownloading.value) return;
    isDownloading.value = true;

    const element = document.getElementById('certificate-card');
    if (!element) {
        isDownloading.value = false;
        return;
    }

    try {
        const canvas = await html2canvas(element, {
            scale: 2.5, // Crisp resolution matching modern landscape vectors
            useCORS: true,
            logging: false,
            backgroundColor: '#ffffff',
        });

        const imgData = canvas.toDataURL('image/jpeg', 1.0);

        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4',
        });

        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = pdf.internal.pageSize.getHeight();

        pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);

        const safeFileName = props.certData.name.replace(/\s+/g, '-');
        pdf.save(`Certificate-${safeFileName}.pdf`);
    } catch (error) {
        console.error('Frontend compilation failure:', error);
        alert('An infrastructure rendering error occurred. Please use alternative printing routes.');
    } finally {
        isDownloading.value = false;
    }
};

// Open browser print dialog
const printLayoutView = () => {
    window.print();
};
</script>

<template>
    <Head :title="`Certificate - ${certData.name}`" />

    <div class="certificate-screen-canvas">
        <div class="workspace-wrapper">
            <!-- Certificate Card -->
            <div id="certificate-card" class="certificate-container">
                <div class="header">Certificate of Completion</div>
                <div class="main-title">YamLMS Certified</div>

                <p class="body-text">This is to certify that</p>
                <div class="name">{{ certData.name }}</div>

                <p class="body-text">has successfully completed the training requirements for</p>
                <div class="course-title">{{ certData.course }}</div>

                <div class="footer">
                    Issued on {{ certData.date }}
                    <br />
                    <strong>Verification ID: {{ certData.certificateId }}</strong>
                    <br />
                    This document is a formal record of professional development.
                </div>
            </div>

            <div class="actions-toolbar no-print">
                <!-- Download Button -->
                <button @click="downloadDirectly" :disabled="isDownloading" class="btn-primary">
                    {{ isDownloading ? 'Downloading...' : 'Download' }}
                </button>

                <!-- Browser Print Trigger -->
                <button @click="printLayoutView" :disabled="isDownloading" class="btn-secondary">Print/Save via Browser</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Layout Styles */
.certificate-screen-canvas {
    min-height: 100vh;
    background-color: #f3f4f6;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    box-sizing: border-box;
}

.workspace-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 24px;
    width: 100%;
    max-width: 842px;
}

/* Certificate Card Styles */
.certificate-container {
    font-family: 'Helvetica', Arial, sans-serif;
    text-align: center;
    border: 20px solid #4f46e5;
    padding: 60px 50px;
    color: #1e1b4b;
    background-color: #ffffff;
    width: 100%;
    box-sizing: border-box;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.header {
    text-transform: uppercase;
    letter-spacing: 5px;
    color: #6366f1;
    font-size: 20px;
    margin-bottom: 20px;
}
.main-title {
    font-size: 50px;
    font-weight: 900;
    margin-bottom: 30px;
}
.body-text {
    font-size: 16px;
    margin: 12px 0;
}
.name {
    font-size: 35px;
    font-style: italic;
    border-bottom: 2px solid #e5e7eb;
    display: inline-block;
    padding: 0 40px;
    margin: 25px 0;
}
.course-title {
    font-size: 25px;
    font-weight: bold;
    color: #4f46e5;
}
.footer {
    margin-top: 50px;
    font-size: 12px;
    color: #9ca3af;
    line-height: 1.6;
}

/* Action Toolbar Styles */
.actions-toolbar {
    display: flex;
    gap: 12px;
}
.btn-primary,
.btn-secondary {
    padding: 14px 28px;
    font-weight: 700;
    font-size: 14px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary {
    background-color: #4f46e5;
    color: #ffffff;
    box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
}
.btn-primary:hover {
    background-color: #4338ca;
    transform: translateY(-1px);
}

.btn-secondary {
    background-color: #ffffff;
    color: #374151;
    border: 1px solid #d1d5db;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}
.btn-secondary:hover {
    background-color: #f9fafb;
    border-color: #c5cbf3;
    color: #4f46e5;
}

.btn-primary:disabled,
.btn-secondary:disabled {
    background-color: #9ca3af;
    color: #ffffff;
    border-color: #9ca3af;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Print Styles */
@media print {
    @page {
        size: landscape;
        margin: 0;
    }

    body,
    html {
        background-color: #ffffff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .certificate-screen-canvas {
        background: none !important;
        padding: 0 !important;
        min-height: auto !important;
    }

    .certificate-container {
        box-shadow: none !important;
        border: 20px solid #4f46e5 !important;
        width: 100% !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .no-print {
        display: none !important;
    }
}
</style>
