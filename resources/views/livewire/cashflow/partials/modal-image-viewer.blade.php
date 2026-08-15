<!-- Foto Struk Resi Viewer Modal (Arus Kas) -->
<x-media-viewer-modal 
    :show="$showImageModal ?? false" 
    type="image" 
    :url="$imageModalUrl ?? ''" 
    :title="$imageModalTitle ?? 'Foto Resi Bukti Transaksi'" 
    closeAction="closeImageModal"
/>
