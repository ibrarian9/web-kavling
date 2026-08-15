<!-- Floating Viewer Modal (Image & PDF Struk / Resi Operasional) -->
<x-media-viewer-modal 
    :show="$showViewerModal ?? false" 
    :type="$viewerType ?? 'auto'" 
    :url="$viewerUrl ?? ''" 
    :title="$viewerTitle ?? 'Pratinjau Berkas Struk / Resi'" 
    closeAction="closeViewer"
/>
