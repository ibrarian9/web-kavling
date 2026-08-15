<!-- PDF & Foto Struk Viewer Modal (Resi Booking & Transfer) -->
<x-media-viewer-modal 
    :show="$showViewerModal ?? false" 
    :type="$viewerType ?? 'auto'" 
    :url="$viewerUrl ?? ''" 
    :title="$viewerTitle ?? 'Pratinjau Resi / Dokumen'" 
    closeAction="closeViewerModal"
/>
