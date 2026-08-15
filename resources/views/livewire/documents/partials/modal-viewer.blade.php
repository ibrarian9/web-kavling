<!-- PDF & Media Viewer Modal (Dokumen SPP & SPJB) -->
<x-media-viewer-modal 
    :show="$showViewerModal ?? false" 
    :type="$viewerType ?? 'pdf'" 
    :url="$viewerUrl ?? ''" 
    :title="$viewerTitle ?? 'Pratinjau Dokumen'" 
    closeAction="closeViewerModal"
/>
