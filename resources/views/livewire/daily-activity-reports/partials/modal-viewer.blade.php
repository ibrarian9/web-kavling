<!-- PDF Viewer Modal for Daily Activity Report -->
<x-media-viewer-modal 
    :show="$showViewerModal ?? false" 
    type="pdf" 
    :url="$viewerUrl ?? ''" 
    :title="$viewerTitle ?? 'Pratinjau Laporan Harian'" 
    closeAction="closeViewerModal"
/>
