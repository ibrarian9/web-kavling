@props([
    'colspan' => 10,
    'title' => 'Belum Ada Data Ditemukan',
    'message' => null,
])

<tr class="table-empty-row">
    <td colspan="{{ $colspan }}" class="p-12 text-center text-slate-400 bg-white rounded-3xl lg:rounded-none border-0">
        <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <p class="font-bold text-slate-600 text-sm">{{ $title }}</p>
        @if($message)
            <p class="text-xs text-slate-400 mt-1">{{ $message }}</p>
        @endif
    </td>
</tr>
