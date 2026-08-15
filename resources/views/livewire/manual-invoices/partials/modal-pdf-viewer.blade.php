<!-- Modal Floating Viewer (Pratinjau PDF di dalam aplikasi) -->
<x-media-viewer-modal 
    :show="$showViewerModal ?? false" 
    type="pdf" 
    :url="$viewerUrl ?? ''" 
    :title="$viewerTitle ?? 'Pratinjau Invoice Manual'" 
    closeAction="closeViewerModal"
/>
