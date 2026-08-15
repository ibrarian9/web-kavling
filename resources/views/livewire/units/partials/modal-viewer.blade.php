<!-- Modal Jendela Melayang (Viewer Modal: Foto Struk / PDF Resi / QR Verifikasi) -->
<x-media-viewer-modal 
    :show="$showViewerModal ?? false" 
    :type="$viewerType ?? 'auto'" 
    :url="$viewerUrl ?? ''" 
    :title="$viewerTitle ?? 'Pratinjau Berkas & Dokumen'" 
    closeAction="closeViewerModal"
/>
